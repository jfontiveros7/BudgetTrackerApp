"""
BudgetTrackerApp — OpenAI Agents SDK Agent
Canonical Agent SDK entrypoint for Budget Tracker App.
It exposes a single root agent for the app while preserving a tool-driven fallback agent.
"""

from __future__ import annotations

import json
import os
import socket
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

    total_income = float(summary["total_income"])
    total_expenses = float(summary["total_expenses"])
    net = float(summary["net"])

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
            reply = f"You are net positive by ${net:.2f} this month."
            if top_expense:
                reply += (
                    f" Your largest expense category is {top_expense['category']} at "
                    f"${float(top_expense['total']):.2f}, so that is the first place to trim."
                )
            reply += " A practical next step is to move part of your surplus into savings right after income arrives."
            return reply
        return (
            f"You are currently net negative by ${abs(net):.2f} this month. "
            "Reduce one recurring expense category first before setting a bigger savings target."
        )

    if "budget" in question and budget_warning:
        return (
            f"Your {budget_warning['category']} budget is at {float(budget_warning['percent_used']):.1f}% "
            f"used, with ${float(budget_warning['spent']):.2f} spent against a ${float(budget_warning['limit']):.2f} limit."
        )

    if "spend" in question or "expense" in question:
        if top_expense:
            return (
                f"Your top expense category this month is {top_expense['category']} at "
                f"${float(top_expense['total']):.2f}. Total expenses are ${total_expenses:.2f} and net cash flow is ${net:.2f}."
            )
        return f"You have ${total_expenses:.2f} in expenses this month and ${net:.2f} net cash flow."

    if "income" in question:
        return (
            f"You have recorded ${total_income:.2f} in income and ${total_expenses:.2f} in expenses this month, "
            f"for a net of ${net:.2f}."
        )

    if recent:
        latest = recent[0]
        return (
            f"Your current month shows ${total_income:.2f} income, ${total_expenses:.2f} expenses, "
            f"and ${net:.2f} net. Your latest transaction was {latest['type']} ${float(latest['amount']):.2f} "
            f"in {latest.get('category_name') or latest.get('category')} on {latest['transaction_date']}."
        )

    return "I could not reach the live AI service, but your budget data is available. Add more transactions and budgets for sharper insights."


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
    context = BudgetAppContext.from_env()
    history = conversation_history or []

    transcript_parts = []
    for item in history:
        role = item.get("role", "user")
        content = item.get("content", "")
        transcript_parts.append(f"{role}: {content}")
    transcript_parts.append(f"user: {user_message}")

    if not _can_reach_openai():
        return _local_budget_fallback(context, user_message)

    try:
        result = await Runner.run(
            root_agent,
            input="\n".join(transcript_parts),
            context=context,
        )
        return result.final_output
    except Exception:
        return _local_budget_fallback(context, user_message)


def run_agent_sync(user_message: str, conversation_history: list[dict[str, Any]] | None = None) -> str:
    context = BudgetAppContext.from_env()
    history = conversation_history or []

    transcript_parts = []
    for item in history:
        role = item.get("role", "user")
        content = item.get("content", "")
        transcript_parts.append(f"{role}: {content}")
    transcript_parts.append(f"user: {user_message}")

    if not _can_reach_openai():
        return _local_budget_fallback(context, user_message)

    try:
        result = Runner.run_sync(
            root_agent,
            input="\n".join(transcript_parts),
            context=context,
        )
        return result.final_output
    except Exception:
        return _local_budget_fallback(context, user_message)
