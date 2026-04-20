from __future__ import annotations

import calendar
from datetime import date, timedelta
from typing import Any
from typing import Literal

from agents import RunContextWrapper, function_tool

from .context import BudgetAppContext
from .db import get_db_connection
from .repository_v2 import (
    add_transaction_v2,
    create_budget_v2,
    delete_transaction_v2,
    get_budget_status_v2,
    get_spending_summary_v2,
    get_transactions_v2,
    list_categories as list_categories_v2,
)


def _period_start(period: str) -> date:
    today = date.today()
    normalized = (period or "monthly").lower()
    if normalized in {"weekly", "week", "7d"}:
        return today - timedelta(days=6)
    if normalized in {"monthly", "month", "30d"}:
        return today.replace(day=1)
    return today - timedelta(days=29)


def _month_projection(current_spend: float) -> tuple[float, str]:
    today = date.today()
    days_in_month = calendar.monthrange(today.year, today.month)[1]
    day_of_month = max(today.day, 1)
    projected = (current_spend / day_of_month) * days_in_month
    if projected > current_spend * 1.2:
        trend = "increasing"
    elif projected < current_spend * 0.95:
        trend = "decreasing"
    else:
        trend = "stable"
    return round(projected, 2), trend


def _build_finance_snapshot(ctx: BudgetAppContext) -> dict[str, Any]:
    summary = get_spending_summary_v2(ctx, "monthly")
    budgets = get_budget_status_v2(ctx)
    categories = [item for item in summary["breakdown"] if item.get("type") == "expense"][:5]
    subscriptions = [
        item for item in summary["breakdown"]
        if item.get("type") == "expense"
        and any(
            keyword in str(item.get("category", "")).lower()
            for keyword in (
                "subscription",
                "subscriptions",
                "netflix",
                "spotify",
                "membership",
                "memberships",
                "software",
            )
        )
    ]

    today = date.today()
    day_of_month = max(today.day, 1)
    days_in_month = calendar.monthrange(today.year, today.month)[1]
    projected_income = (
        (float(summary["total_income"]) / day_of_month) * days_in_month
        if float(summary["total_income"])
        else 0.0
    )
    projected_expenses = (
        (float(summary["total_expenses"]) / day_of_month) * days_in_month
        if float(summary["total_expenses"])
        else 0.0
    )
    forecast_eom_balance = round(projected_income - projected_expenses, 2)

    max_budget_use = max((float(item.get("percent_used", 0)) for item in budgets), default=0.0)
    if float(summary["net"]) < 0 or max_budget_use >= 100:
        overspending_risk = "high"
    elif max_budget_use >= 80 or (
        float(summary["total_income"]) > 0
        and float(summary["total_expenses"]) >= float(summary["total_income"]) * 0.9
    ):
        overspending_risk = "medium"
    else:
        overspending_risk = "low"

    return {
        "income_30d": round(float(summary["total_income"]), 2),
        "expenses_30d": round(float(summary["total_expenses"]), 2),
        "net": round(float(summary["net"]), 2),
        "top_categories": categories,
        "subscriptions": subscriptions,
        "overspending_risk": overspending_risk,
        "forecast_eom_balance": forecast_eom_balance,
    }


@function_tool
def create_transaction(
    ctx: RunContextWrapper[BudgetAppContext],
    category: str,
    description: str,
    amount: float,
    transaction_type: Literal["income", "expense"],
) -> str:
    """Create a new transaction for the active user.

    Args:
        category: The transaction category.
        description: The transaction description.
        amount: The transaction amount.
        transaction_type: Either income or expense.
    """
    result = add_transaction_v2(ctx.context, amount, category, transaction_type, description)
    return f"Transaction created with id {result['id']}."


@function_tool
def add_transaction(
    ctx: RunContextWrapper[BudgetAppContext],
    amount: float,
    category: str,
    merchant: str = "",
    date: str | None = None,
    type: Literal["income", "expense"] = "expense",
) -> dict:
    """Add a new transaction using the app's preferred signature.

    Args:
        amount: The numeric transaction amount.
        category: The transaction category.
        merchant: Merchant or payee name, stored in the description field.
        date: Optional transaction date in YYYY-MM-DD format.
        type: Either income or expense.
    """
    return add_transaction_v2(ctx.context, amount, category, type, merchant, date)


@function_tool
def list_recent_transactions(
    ctx: RunContextWrapper[BudgetAppContext],
    limit: int = 10,
) -> str:
    """List recent transactions for the active user.

    Args:
        limit: Maximum number of transactions to return.
    """
    rows = get_transactions_v2(ctx.context, limit=limit)
    if not rows:
        return "No transactions found."
    return "\n".join(
        f'#{row["id"]}: {row["type"]} ${row["amount"]:.2f} in {row.get("category_name") or row["category"]} on {row["transaction_date"]}'
        for row in rows
    )


@function_tool
def list_transactions(
    ctx: RunContextWrapper[BudgetAppContext],
    limit: int = 20,
) -> list[dict]:
    """List recent transactions as structured records.

    Args:
        limit: Maximum number of transactions to return.
    """
    return get_transactions_v2(ctx.context, limit=limit)


@function_tool
def update_transaction_description(
    ctx: RunContextWrapper[BudgetAppContext],
    transaction_id: int,
    description: str,
) -> str:
    """Update the description for a transaction.

    Args:
        transaction_id: Transaction id to update.
        description: New description text.
    """
    with get_db_connection(ctx.context) as conn:
        cursor = conn.cursor()
        cursor.execute(
            """
            UPDATE transactions
            SET description = %s
            WHERE id = %s AND user_id = %s
            """,
            (description, transaction_id, ctx.context.user_id),
        )
        conn.commit()
        return f"Updated {cursor.rowcount} transaction(s)."


@function_tool
def delete_transaction(
    ctx: RunContextWrapper[BudgetAppContext],
    transaction_id: int,
) -> str:
    """Delete a transaction for the active user.

    Args:
        transaction_id: Transaction id to delete.
    """
    result = delete_transaction_v2(ctx.context, transaction_id)
    return f"Transaction {transaction_id} status: {result['status']}."


@function_tool
def create_budget(
    ctx: RunContextWrapper[BudgetAppContext],
    category: str,
    amount: float,
) -> str:
    """Create a budget record for the active user.

    Args:
        category: Budget category.
        amount: Budget amount.
    """
    result = create_budget_v2(ctx.context, f"{category} Budget", category, amount)
    if result.get("status") == "error":
        return result.get("message", "Budget creation failed.")
    return f"Budget created with id {result['id']}."


@function_tool
def update_budget(
    ctx: RunContextWrapper[BudgetAppContext],
    category: str,
    amount: float,
) -> dict:
    """Update the active budget amount for a category.

    Args:
        category: Budget category to update.
        amount: New budget amount.
    """
    with get_db_connection(ctx.context) as conn:
        cursor = conn.cursor(dictionary=True)
        try:
            cursor.execute(
                """
                SELECT
                    b.id,
                    COALESCE(c.name, b.category) AS category_name
                FROM budgets b
                LEFT JOIN categories c ON b.category_id = c.id
                WHERE b.user_id = %s
                  AND b.is_active = TRUE
                  AND LOWER(COALESCE(c.name, b.category)) = LOWER(%s)
                ORDER BY b.id DESC
                LIMIT 1
                """,
                (ctx.context.user_id, category),
            )
            row = cursor.fetchone()
            if not row:
                return {
                    "status": "not_found",
                    "updated": 0,
                    "category": category,
                    "amount": float(amount),
                }

            budget_id = int(row["id"])
            resolved_category = row["category_name"]
            cursor.execute(
                """
                UPDATE budgets
                SET amount = %s, amount_limit = %s
                WHERE id = %s AND user_id = %s
                """,
                (amount, amount, budget_id, ctx.context.user_id),
            )
            conn.commit()
            return {
                "status": "success",
                "updated": cursor.rowcount,
                "budget_id": budget_id,
                "category": resolved_category,
                "amount": float(amount),
            }
        finally:
            cursor.close()


@function_tool
def list_budgets(ctx: RunContextWrapper[BudgetAppContext]) -> str:
    """List budgets for the active user."""
    budgets = get_budget_status_v2(ctx.context)
    if not budgets:
        return "No budgets found."
    return "\n".join(
        f'#{row["id"]}: {row["category"]} budget is ${row["limit"]:.2f} and spent ${row["spent"]:.2f}'
        for row in budgets
    )


@function_tool
def update_budget_amount(
    ctx: RunContextWrapper[BudgetAppContext],
    budget_id: int,
    amount: float,
) -> str:
    """Update an existing budget amount.

    Args:
        budget_id: Budget id to update.
        amount: New budget amount.
    """
    with get_db_connection(ctx.context) as conn:
        cursor = conn.cursor()
        try:
            cursor.execute(
                """
                UPDATE budgets
                SET amount = %s, amount_limit = %s
                WHERE id = %s AND user_id = %s
                """,
                (amount, amount, budget_id, ctx.context.user_id),
            )
            conn.commit()
            return f"Updated {cursor.rowcount} budget(s)."
        finally:
            cursor.close()


@function_tool
def delete_budget(
    ctx: RunContextWrapper[BudgetAppContext],
    budget_id: int,
) -> str:
    """Delete a budget record.

    Args:
        budget_id: Budget id to delete.
    """
    with get_db_connection(ctx.context) as conn:
        cursor = conn.cursor()
        cursor.execute(
            "DELETE FROM budgets WHERE id = %s AND user_id = %s",
            (budget_id, ctx.context.user_id),
        )
        conn.commit()
        return f"Deleted {cursor.rowcount} budget(s)."


@function_tool
def summarize_budget_health(ctx: RunContextWrapper[BudgetAppContext]) -> str:
    """Summarize total income, expense, and net balance for the active user."""
    summary = get_spending_summary_v2(ctx.context, "monthly")
    income = float(summary["total_income"])
    expense = float(summary["total_expenses"])
    net = float(summary["net"])
    return f"Income: ${income:.2f}, Expense: ${expense:.2f}, Net: ${net:.2f}"


@function_tool
def get_budget_status(ctx: RunContextWrapper[BudgetAppContext]) -> dict:
    """Get all active budgets with current spending vs limits."""
    return {"budgets": get_budget_status_v2(ctx.context)}


@function_tool
def analyze_monthly_trends(ctx: RunContextWrapper[BudgetAppContext]) -> str:
    """Analyze transaction trends using pandas when available, with a SQL fallback."""
    rows = get_transactions_v2(ctx.context, start_date=_period_start("30d").isoformat(), limit=100)
    if not rows:
        return "No recent transaction data available."

    try:
        import pandas as pd

        df = pd.DataFrame(rows)
        grouped = df.groupby("type")["amount"].sum().to_dict()
        income = float(grouped.get("income", 0.0))
        expense = float(grouped.get("expense", 0.0))
        net = income - expense
        return (
            "30-day trend summary using pandas: "
            f"income=${income:.2f}, expense=${expense:.2f}, net=${net:.2f}, "
            f"transactions={len(df)}"
        )
    except Exception:
        income = sum(float(row["amount"]) for row in rows if row["type"] == "income")
        expense = sum(float(row["amount"]) for row in rows if row["type"] == "expense")
        net = income - expense
        return (
            "30-day trend summary using SQL fallback: "
            f"income=${income:.2f}, expense=${expense:.2f}, net=${net:.2f}, "
            f"transactions={len(rows)}"
        )


@function_tool
def get_spending_summary(
    ctx: RunContextWrapper[BudgetAppContext],
    period: str = "monthly",
) -> dict:
    """Get spending breakdown by category for the given period.

    Args:
        period: Supported values include weekly, monthly, and 30d.
    """
    summary = get_spending_summary_v2(ctx.context, period)
    return {
        "period": summary["period"],
        "total": summary["total_expenses"],
        "categories": summary["breakdown"],
    }


@function_tool
def forecast_spending(ctx: RunContextWrapper[BudgetAppContext]) -> dict:
    """Predict end-of-month spending using Python analytics."""
    summary = get_spending_summary_v2(ctx.context, "monthly")
    current_spend = float(summary["total_expenses"])
    projected, trend = _month_projection(current_spend)
    return {"projected": projected, "trend": trend}


@function_tool
def get_forecast(ctx: RunContextWrapper[BudgetAppContext]) -> dict:
    """Get the current end-of-month forecast."""
    forecast = forecast_spending(ctx)
    snapshot = _build_finance_snapshot(ctx.context)
    return {
        "projected_expenses": forecast["projected"],
        "trend": forecast["trend"],
        "forecast_eom_balance": snapshot["forecast_eom_balance"],
    }


@function_tool
def list_categories(ctx: RunContextWrapper[BudgetAppContext]) -> str:
    """List distinct categories used across budgets and transactions."""
    categories = [row["name"] for row in list_categories_v2(ctx.context) if row.get("name")]
    if not categories:
        return "No categories found."
    return ", ".join(categories)


@function_tool
def rename_category(
    ctx: RunContextWrapper[BudgetAppContext],
    old_name: str,
    new_name: str,
) -> str:
    """Rename a category across transactions and budgets.

    Args:
        old_name: Existing category name.
        new_name: Replacement category name.
    """
    with get_db_connection(ctx.context) as conn:
        cursor = conn.cursor()
        cursor.execute(
            """
            UPDATE transactions
            SET category = %s
            WHERE user_id = %s AND category = %s
            """,
            (new_name, ctx.context.user_id, old_name),
        )
        tx_count = cursor.rowcount
        cursor.execute(
            """
            UPDATE budgets
            SET category = %s
            WHERE user_id = %s AND category = %s
            """,
            (new_name, ctx.context.user_id, old_name),
        )
        budget_count = cursor.rowcount
        cursor.execute(
            """
            UPDATE categories
            SET name = %s
            WHERE LOWER(name) = LOWER(%s)
            """,
            (new_name, old_name),
        )
        conn.commit()
    return f"Renamed category in {tx_count} transaction(s) and {budget_count} budget record(s)."


@function_tool
def finance_snapshot_for_insights(ctx: RunContextWrapper[BudgetAppContext]) -> str:
    """Return a compact finance snapshot for the Insights Agent to reason over."""
    snapshot = _build_finance_snapshot(ctx.context)
    return f"User financial snapshot: {snapshot}"


@function_tool
def get_top_categories(ctx: RunContextWrapper[BudgetAppContext]) -> list[dict[str, Any]]:
    """Get the top expense categories for the current month."""
    return _build_finance_snapshot(ctx.context)["top_categories"]


@function_tool
def get_subscription_list(ctx: RunContextWrapper[BudgetAppContext]) -> list[dict[str, Any]]:
    """Get likely subscription-related categories for the current month."""
    return _build_finance_snapshot(ctx.context)["subscriptions"]


@function_tool
def get_overspending_risk(ctx: RunContextWrapper[BudgetAppContext]) -> dict[str, Any]:
    """Get the current overspending risk classification."""
    snapshot = _build_finance_snapshot(ctx.context)
    return {
        "overspending_risk": snapshot["overspending_risk"],
        "forecast_eom_balance": snapshot["forecast_eom_balance"],
        "net": snapshot["net"],
    }
