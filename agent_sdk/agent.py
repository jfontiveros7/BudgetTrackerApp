"""
BudgetTrackerApp — OpenAI Agents SDK Agent
Canonical Agent SDK entrypoint for Budget Tracker App.
It exposes a single root agent for the app while preserving a tool-driven fallback agent.
"""

from __future__ import annotations

import json
import logging
import os
import socket
import calendar
from datetime import date
from typing import Any
from urllib.parse import urlparse

from agents import Agent, RunContextWrapper, Runner, function_tool

from .coordinator import budget_coordinator_agent
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


logger = logging.getLogger(__name__)


def _is_invalid_loopback_proxy(proxy: str | None) -> bool:
    if not proxy:
        return False

    parsed = urlparse(proxy)
    host = (parsed.hostname or "").lower()
    port = parsed.port or (443 if parsed.scheme == "https" else 80)
    return host in {"127.0.0.1", "localhost"} and port == 9


def _clear_invalid_proxy_env() -> None:
    for name in ("HTTPS_PROXY", "https_proxy", "ALL_PROXY", "all_proxy", "HTTP_PROXY", "http_proxy"):
        value = os.getenv(name)
        if _is_invalid_loopback_proxy(value):
            os.environ.pop(name, None)


@function_tool
def tool_add_transaction(
    ctx: RunContextWrapper[BudgetAppContext],
    amount: float,
    category: str,
    type: str,
    description: str = "",
    date: str | None = None,
) -> str:
    """Add a new income or expense transaction.

    Args:
        amount: Transaction amount in dollars.
        category: Category name such as Groceries, Rent, or Salary.
        type: Whether this is income or expense.
        description: Brief description of the transaction.
        date: Transaction date in YYYY-MM-DD format. Defaults to today.
    """
    return json.dumps(
        add_transaction_v2(ctx.context, amount, category, type, description, date),
        default=str,
    )


@function_tool
def tool_get_transactions(
    ctx: RunContextWrapper[BudgetAppContext],
    start_date: str | None = None,
    end_date: str | None = None,
    category: str | None = None,
    type: str | None = None,
    limit: int = 20,
) -> str:
    """List transactions with optional filters by date range, category, or type."""
    results = get_transactions_v2(ctx.context, start_date, end_date, category, type, limit)
    return json.dumps(results if results else {"message": "No transactions found."}, default=str)


@function_tool
def tool_delete_transaction(
    ctx: RunContextWrapper[BudgetAppContext],
    transaction_id: int,
) -> str:
    """Delete a transaction by its ID."""
    return json.dumps(delete_transaction_v2(ctx.context, transaction_id), default=str)


@function_tool
def tool_create_budget(
    ctx: RunContextWrapper[BudgetAppContext],
    name: str,
    category: str,
    amount_limit: float,
    period: str = "monthly",
) -> str:
    """Create a new budget with a spending limit for a category."""
    return json.dumps(
        create_budget_v2(ctx.context, name, category, amount_limit, period),
        default=str,
    )


@function_tool
def tool_get_budget_status(
    ctx: RunContextWrapper[BudgetAppContext],
    category: str | None = None,
) -> str:
    """Get all active budgets with current spending vs limits and percentage used."""
    results = get_budget_status_v2(ctx.context, category)
    return json.dumps(results if results else {"message": "No active budgets found."}, default=str)


@function_tool
def tool_get_spending_summary(
    ctx: RunContextWrapper[BudgetAppContext],
    period: str = "monthly",
) -> str:
    """Get spending breakdown by category for a given period."""
    return json.dumps(get_spending_summary_v2(ctx.context, period), default=str)


@function_tool
def tool_list_categories(
    ctx: RunContextWrapper[BudgetAppContext],
) -> str:
    """List all available transaction categories."""
    return json.dumps(list_categories_v2(ctx.context), default=str)


SYSTEM_PROMPT = """You are Budget Tracker App, a personal finance assistant built by Konticode Labs.

Your capabilities:
- Add, list, and delete income and expense transactions
- Create and monitor budgets with spending limits
- Provide spending summaries and insights by category
- Forecast end-of-month spending based on trends
- Manage expense and income categories

Rules:
- Always confirm amounts and categories before adding transactions
- Use dollar formatting ($1,234.56)
- Warn when spending exceeds 80% of a budget limit
- Be concise and actionable
- When possible, reason from this financial snapshot shape:
  User financial snapshot:
  - Income (30d): $X
  - Expenses (30d): $Y
  - Net: $Z
  - Top categories: ...
  - Subscriptions: ...
  - Overspending risk: ...
  - Forecast EOM balance: ...
- Provide insights, warnings, and suggestions based on that data
- When listing transactions, show: date, category, amount, description
- Default period is current month unless specified
- If a user asks about something outside budgeting or finance, politely redirect them
"""


tool_budget_agent = Agent(
    name="Budget Coordinator (Tool Mode)",
    model="gpt-5",
    instructions=SYSTEM_PROMPT,
    tools=[
        tool_add_transaction,
        tool_get_transactions,
        tool_delete_transaction,
        tool_create_budget,
        tool_get_budget_status,
        tool_get_spending_summary,
        tool_list_categories,
    ],
)


# Canonical app-wide root agent. This keeps the richer coordinator + handoff
# system as the official execution path for CLI, PHP, and future integrations.
root_agent = budget_coordinator_agent


def _can_reach_openai(timeout_seconds: float = 2.0) -> bool:
    _clear_invalid_proxy_env()

    if not os.getenv("OPENAI_API_KEY"):
        return False

    proxy_candidates = [
        os.getenv("HTTPS_PROXY"),
        os.getenv("https_proxy"),
        os.getenv("ALL_PROXY"),
        os.getenv("all_proxy"),
        os.getenv("HTTP_PROXY"),
        os.getenv("http_proxy"),
    ]
    for proxy in proxy_candidates:
        if not proxy:
            continue
        parsed = urlparse(proxy)
        proxy_host = parsed.hostname
        proxy_port = parsed.port or (443 if parsed.scheme == "https" else 80)
        if proxy_host:
            try:
                with socket.create_connection((proxy_host, proxy_port), timeout=0.5):
                    return True
            except OSError:
                return False

    try:
        with socket.create_connection(("api.openai.com", 443), timeout=timeout_seconds):
            return True
    except OSError:
        return False


def _project_end_of_month_balance(total_income: float, total_expenses: float) -> float:
    today = date.today()
    day_of_month = max(today.day, 1)
    days_in_month = calendar.monthrange(today.year, today.month)[1]
    projected_income = (total_income / day_of_month) * days_in_month if total_income else 0.0
    projected_expenses = (total_expenses / day_of_month) * days_in_month if total_expenses else 0.0
    return round(projected_income - projected_expenses, 2)


def _build_financial_signals(
    summary: dict[str, Any],
    budgets: list[dict[str, Any]],
    recent: list[dict[str, Any]],
) -> dict[str, Any]:
    total_income = float(summary["total_income"])
    total_expenses = float(summary["total_expenses"])
    net = float(summary["net"])

    expense_categories = [
        item for item in summary.get("breakdown", [])
        if item.get("type") == "expense"
    ]
    top_categories = sorted(
        expense_categories,
        key=lambda item: float(item.get("total", 0)),
        reverse=True,
    )[:3]

    subscription_keywords = (
        "subscription", "subscriptions", "netflix", "spotify", "hulu",
        "disney", "prime", "membership", "memberships", "software", "saas"
    )
    subscriptions = []
    seen_categories: set[str] = set()
    for item in expense_categories:
        category_name = str(item.get("category", ""))
        lowered = category_name.lower()
        if any(keyword in lowered for keyword in subscription_keywords):
            if category_name not in seen_categories:
                subscriptions.append(
                    {
                        "category": category_name,
                        "amount": round(float(item.get("total", 0)), 2),
                    }
                )
                seen_categories.add(category_name)

    max_budget_use = max((float(item.get("percent_used", 0)) for item in budgets), default=0.0)
    if net < 0 or max_budget_use >= 100:
        overspending_risk = "high"
    elif max_budget_use >= 80 or (total_income > 0 and total_expenses >= total_income * 0.9):
        overspending_risk = "medium"
    else:
        overspending_risk = "low"

    forecast_eom_balance = _project_end_of_month_balance(total_income, total_expenses)

    return {
        "income_30d": round(total_income, 2),
        "expenses_30d": round(total_expenses, 2),
        "net": round(net, 2),
        "top_categories": top_categories,
        "subscriptions": subscriptions,
        "overspending_risk": overspending_risk,
        "forecast_eom_balance": forecast_eom_balance,
        "recent_transactions_count": len(recent),
    }


def _format_financial_snapshot(signals: dict[str, Any]) -> str:
    top_categories = ", ".join(
        f"{item['category']} (${float(item['total']):.2f})"
        for item in signals["top_categories"]
    ) or "None yet"
    subscriptions = ", ".join(
        f"{item['category']} (${float(item['amount']):.2f})"
        for item in signals["subscriptions"]
    ) or "None detected"

    return (
        "User financial snapshot:\n"
        f"- Income (30d): ${signals['income_30d']:.2f}\n"
        f"- Expenses (30d): ${signals['expenses_30d']:.2f}\n"
        f"- Net: ${signals['net']:.2f}\n"
        f"- Top categories: {top_categories}\n"
        f"- Subscriptions: {subscriptions}\n"
        f"- Overspending risk: {signals['overspending_risk']}\n"
        f"- Forecast EOM balance: ${signals['forecast_eom_balance']:.2f}"
    )


def _local_budget_fallback(context: BudgetAppContext, user_message: str) -> str:
    question = (user_message or "").strip().lower()
    try:
        summary = get_spending_summary_v2(context, "monthly")
        budgets = get_budget_status_v2(context)
        recent = get_transactions_v2(context, limit=8)
    except Exception:
        summary = _legacy_monthly_summary(context)
        budgets = _legacy_budget_status(context)
        recent = _legacy_recent_transactions(context)

    signals = _build_financial_signals(summary, budgets, recent)
    total_income = float(signals["income_30d"])
    total_expenses = float(signals["expenses_30d"])
    net = float(signals["net"])
    snapshot = _format_financial_snapshot(signals)

    top_expense = next(
        (item for item in summary["breakdown"] if item.get("type") == "expense"),
        None,
    )
    budget_warning = next(
        (item for item in budgets if float(item.get("percent_used", 0)) >= 80),
        None,
    )

    if "save" in question or "saving" in question:
        if net > 0:
            reply = snapshot + "\n\n"
            reply += f"Insight: You are net positive by ${net:.2f} this month."
            if top_expense:
                reply += (
                    f" Your largest expense category is {top_expense['category']} at "
                    f"${float(top_expense['total']):.2f}, so that is the first place to trim."
                )
            reply += " Suggestion: Move part of your surplus into savings right after income arrives."
            return reply
        return (
            snapshot + "\n\n"
            + f"Warning: You are currently net negative by ${abs(net):.2f} this month. "
            + "Suggestion: Reduce one recurring expense category first before setting a bigger savings target."
        )

    if "budget" in question and budget_warning:
        return (
            snapshot + "\n\n"
            + f"Warning: Your {budget_warning['category']} budget is at {float(budget_warning['percent_used']):.1f}% "
            + f"used, with ${float(budget_warning['spent']):.2f} spent against a ${float(budget_warning['limit']):.2f} limit. "
            + "Suggestion: Slow spending in that category or raise the budget if this is a planned increase."
        )

    if "spend" in question or "expense" in question:
        if top_expense:
            return (
                snapshot + "\n\n"
                + f"Insight: Your top expense category this month is {top_expense['category']} at "
                + f"${float(top_expense['total']):.2f}. "
                + "Suggestion: Review whether that category is fixed, discretionary, or subscription-driven."
            )
        return snapshot + "\n\n" + f"Insight: You have ${total_expenses:.2f} in expenses this month and ${net:.2f} net cash flow."

    if "income" in question:
        return (
            snapshot + "\n\n"
            + f"Insight: You have recorded ${total_income:.2f} in income and ${total_expenses:.2f} in expenses this month, "
            + f"for a net of ${net:.2f}."
        )

    if recent:
        latest = recent[0]
        return (
            snapshot + "\n\n"
            + f"Insight: Your latest transaction was {latest['type']} ${float(latest['amount']):.2f} "
            + f"in {latest.get('category_name') or latest.get('category')} on {latest['transaction_date']}. "
            + "Suggestion: Use the top categories and subscriptions above to decide what to reduce next."
        )

    return snapshot + "\n\nInsight: I could not reach the live AI service, but your budget data is available. Suggestion: Add more transactions and budgets for sharper insights."


def _build_fallback_response(context: BudgetAppContext, user_message: str, reason: str) -> str:
    return f"{reason}\n\n{_local_budget_fallback(context, user_message)}"


def _legacy_monthly_summary(context: BudgetAppContext) -> dict[str, Any]:
    with get_db_connection(context) as conn:
        cursor = conn.cursor(dictionary=True)
        try:
            cursor.execute(
                """
                SELECT
                    category,
                    type,
                    SUM(amount) AS total,
                    COUNT(*) AS transaction_count
                FROM transactions
                WHERE user_id = %s
                  AND created_at >= DATE_FORMAT(CURDATE(), '%%Y-%%m-01')
                GROUP BY category, type
                ORDER BY total DESC
                """,
                (context.user_id,),
            )
            rows = cursor.fetchall()
        finally:
            cursor.close()

    total_income = 0.0
    total_expenses = 0.0
    breakdown: list[dict[str, Any]] = []
    for row in rows:
        amount = float(row["total"] or 0)
        if row["type"] == "income":
            total_income += amount
        else:
            total_expenses += amount
        breakdown.append(
            {
                "category": row["category"],
                "type": row["type"],
                "total": amount,
                "transaction_count": int(row["transaction_count"] or 0),
            }
        )

    return {
        "period": "monthly",
        "total_income": round(total_income, 2),
        "total_expenses": round(total_expenses, 2),
        "net": round(total_income - total_expenses, 2),
        "breakdown": breakdown,
    }


def _legacy_budget_status(context: BudgetAppContext) -> list[dict[str, Any]]:
    with get_db_connection(context) as conn:
        cursor = conn.cursor(dictionary=True)
        try:
            cursor.execute(
                """
                SELECT
                    b.id,
                    b.category,
                    b.amount,
                    COALESCE(SUM(
                        CASE
                            WHEN t.type = 'expense'
                             AND t.created_at >= DATE_FORMAT(CURDATE(), '%%Y-%%m-01')
                            THEN t.amount
                            ELSE 0
                        END
                    ), 0) AS spent
                FROM budgets b
                LEFT JOIN transactions t
                    ON t.user_id = b.user_id
                   AND t.category = b.category
                WHERE b.user_id = %s
                GROUP BY b.id, b.category, b.amount
                ORDER BY b.category ASC
                """,
                (context.user_id,),
            )
            rows = cursor.fetchall()
        finally:
            cursor.close()

    results: list[dict[str, Any]] = []
    for row in rows:
        limit_val = float(row["amount"] or 0)
        spent_val = float(row["spent"] or 0)
        pct = round((spent_val / limit_val) * 100, 1) if limit_val > 0 else 0.0
        results.append(
            {
                "id": row["id"],
                "category": row["category"],
                "limit": limit_val,
                "spent": spent_val,
                "percent_used": pct,
            }
        )
    return results


def _legacy_recent_transactions(context: BudgetAppContext) -> list[dict[str, Any]]:
    with get_db_connection(context) as conn:
        cursor = conn.cursor(dictionary=True)
        try:
            cursor.execute(
                """
                SELECT id, category, description, amount, type, created_at
                FROM transactions
                WHERE user_id = %s
                ORDER BY created_at DESC, id DESC
                LIMIT 8
                """,
                (context.user_id,),
            )
            rows = cursor.fetchall()
        finally:
            cursor.close()

    for row in rows:
        row["amount"] = float(row["amount"] or 0)
        row["transaction_date"] = str(row["created_at"]).split(" ")[0]
        row["category_name"] = row.get("category")
    return rows


async def run_agent(user_message: str, conversation_history: list[dict[str, Any]] | None = None) -> str:
    _clear_invalid_proxy_env()
    context = BudgetAppContext.from_env()
    history = conversation_history or []

    transcript_parts = []
    for item in history:
        role = item.get("role", "user")
        content = item.get("content", "")
        transcript_parts.append(f"{role}: {content}")
    transcript_parts.append(f"user: {user_message}")

    if not _can_reach_openai():
        return _build_fallback_response(
            context,
            user_message,
            "Live AI service is unavailable right now, so the app is using local budget logic instead.",
        )

    try:
        result = await Runner.run(
            root_agent,
            input="\n".join(transcript_parts),
            context=context,
        )
        return result.final_output
    except Exception as exc:
        logger.exception("Agent runtime failed; using local budget fallback.")
        return _build_fallback_response(
            context,
            user_message,
            f"Live AI agent failed ({type(exc).__name__}: {exc}), so the app is using local budget logic instead.",
        )


def run_agent_sync(user_message: str, conversation_history: list[dict[str, Any]] | None = None) -> str:
    _clear_invalid_proxy_env()
    context = BudgetAppContext.from_env()
    history = conversation_history or []

    transcript_parts = []
    for item in history:
        role = item.get("role", "user")
        content = item.get("content", "")
        transcript_parts.append(f"{role}: {content}")
    transcript_parts.append(f"user: {user_message}")

    if not _can_reach_openai():
        return _build_fallback_response(
            context,
            user_message,
            "Live AI service is unavailable right now, so the app is using local budget logic instead.",
        )

    try:
        result = Runner.run_sync(
            root_agent,
            input="\n".join(transcript_parts),
            context=context,
        )
        return result.final_output
    except Exception as exc:
        logger.exception("Agent runtime failed; using local budget fallback.")
        return _build_fallback_response(
            context,
            user_message,
            f"Live AI agent failed ({type(exc).__name__}: {exc}), so the app is using local budget logic instead.",
        )
