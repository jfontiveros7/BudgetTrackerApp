"""
Budget Tracker App - v2-compatible repository layer

This module targets the richer schema in `sql/schema_v2_compatible.sql` while
remaining compatible with the app's user-owned data model.

It preserves:
- user_id ownership on budgets and transactions
- category text fields

It also supports:
- categories table
- category_id foreign keys
- transaction_date
- amount_limit / period / is_active on budgets
"""

from __future__ import annotations

from datetime import date, timedelta
from typing import Any

from .context import BudgetAppContext
from .db import get_db_connection


def get_or_create_category(
    ctx: BudgetAppContext,
    name: str,
    cat_type: str = "expense",
    icon: str | None = None,
) -> int:
    with get_db_connection(ctx) as conn:
        cursor = conn.cursor(dictionary=True)
        try:
            cursor.execute(
                "SELECT id FROM categories WHERE LOWER(name) = LOWER(%s)",
                (name,),
            )
            row = cursor.fetchone()
            if row:
                return int(row["id"])

            cursor.execute(
                """
                INSERT INTO categories (name, type, icon)
                VALUES (%s, %s, %s)
                """,
                (name, cat_type, icon),
            )
            conn.commit()
            return int(cursor.lastrowid)
        finally:
            cursor.close()


def list_categories(ctx: BudgetAppContext) -> list[dict[str, Any]]:
    with get_db_connection(ctx) as conn:
        cursor = conn.cursor(dictionary=True)
        try:
            cursor.execute(
                """
                SELECT id, name, type, icon, created_at
                FROM categories
                ORDER BY type, name
                """
            )
            return cursor.fetchall()
        finally:
            cursor.close()


def add_transaction_v2(
    ctx: BudgetAppContext,
    amount: float,
    category: str,
    txn_type: str,
    description: str = "",
    txn_date: str | None = None,
) -> dict[str, Any]:
    category_id = get_or_create_category(ctx, category, txn_type)
    if txn_date is None:
        txn_date = date.today().isoformat()

    with get_db_connection(ctx) as conn:
        cursor = conn.cursor(dictionary=True)
        try:
            cursor.execute(
                """
                INSERT INTO transactions (
                    user_id, category, category_id, amount, description, type, transaction_date
                )
                VALUES (%s, %s, %s, %s, %s, %s, %s)
                """,
                (ctx.user_id, category, category_id, amount, description, txn_type, txn_date),
            )
            conn.commit()
            return {
                "id": int(cursor.lastrowid),
                "user_id": ctx.user_id,
                "amount": float(amount),
                "type": txn_type,
                "category": category,
                "category_id": category_id,
                "description": description,
                "transaction_date": txn_date,
                "status": "created",
            }
        finally:
            cursor.close()


def get_transactions_v2(
    ctx: BudgetAppContext,
    start_date: str | None = None,
    end_date: str | None = None,
    category: str | None = None,
    txn_type: str | None = None,
    limit: int = 20,
) -> list[dict[str, Any]]:
    limit = max(1, min(100, int(limit)))
    with get_db_connection(ctx) as conn:
        cursor = conn.cursor(dictionary=True)
        try:
            query = """
                SELECT
                    t.id,
                    t.user_id,
                    t.amount,
                    t.type,
                    t.category,
                    t.category_id,
                    t.description,
                    t.transaction_date,
                    t.created_at,
                    c.name AS category_name
                FROM transactions t
                LEFT JOIN categories c ON t.category_id = c.id
                WHERE t.user_id = %s
            """
            params: list[Any] = [ctx.user_id]

            if start_date:
                query += " AND t.transaction_date >= %s"
                params.append(start_date)
            if end_date:
                query += " AND t.transaction_date <= %s"
                params.append(end_date)
            if category:
                query += " AND LOWER(COALESCE(c.name, t.category)) = LOWER(%s)"
                params.append(category)
            if txn_type:
                query += " AND t.type = %s"
                params.append(txn_type)

            query += " ORDER BY t.transaction_date DESC, t.id DESC LIMIT %s"
            params.append(limit)
            cursor.execute(query, params)
            rows = cursor.fetchall()

            for row in rows:
                row["amount"] = float(row["amount"])
                row["transaction_date"] = str(row["transaction_date"])

            return rows
        finally:
            cursor.close()


def delete_transaction_v2(ctx: BudgetAppContext, txn_id: int) -> dict[str, Any]:
    with get_db_connection(ctx) as conn:
        cursor = conn.cursor()
        try:
            cursor.execute(
                "DELETE FROM transactions WHERE id = %s AND user_id = %s",
                (txn_id, ctx.user_id),
            )
            conn.commit()
            return {
                "id": txn_id,
                "status": "deleted" if cursor.rowcount > 0 else "not_found",
            }
        finally:
            cursor.close()


def create_budget_v2(
    ctx: BudgetAppContext,
    name: str,
    category: str,
    amount_limit: float,
    period: str = "monthly",
) -> dict[str, Any]:
    category_id = get_or_create_category(ctx, category, "expense")
    with get_db_connection(ctx) as conn:
        cursor = conn.cursor(dictionary=True)
        try:
            cursor.execute(
                """
                SELECT id
                FROM budgets
                WHERE user_id = %s AND category_id = %s AND is_active = TRUE
                """,
                (ctx.user_id, category_id),
            )
            if cursor.fetchone():
                return {
                    "status": "error",
                    "message": f"Active budget already exists for '{category}'.",
                }

            cursor.execute(
                """
                INSERT INTO budgets (
                    user_id, name, category, category_id, amount, amount_limit, period, is_active
                )
                VALUES (%s, %s, %s, %s, %s, %s, %s, TRUE)
                """,
                (ctx.user_id, name, category, category_id, amount_limit, amount_limit, period),
            )
            conn.commit()
            return {
                "id": int(cursor.lastrowid),
                "name": name,
                "category": category,
                "category_id": category_id,
                "limit": float(amount_limit),
                "period": period,
                "status": "created",
            }
        finally:
            cursor.close()


def get_budget_status_v2(
    ctx: BudgetAppContext,
    category: str | None = None,
) -> list[dict[str, Any]]:
    with get_db_connection(ctx) as conn:
        cursor = conn.cursor(dictionary=True)
        try:
            month_start = date.today().replace(day=1).isoformat()
            query = """
                SELECT
                    b.id,
                    b.name,
                    COALESCE(c.name, b.category) AS category_name,
                    b.amount_limit,
                    b.period,
                    b.is_active,
                    COALESCE(SUM(
                        CASE
                            WHEN t.type = 'expense' AND t.transaction_date >= %s
                            THEN t.amount
                            ELSE 0
                        END
                    ), 0) AS spent
                FROM budgets b
                LEFT JOIN categories c ON b.category_id = c.id
                LEFT JOIN transactions t
                    ON t.user_id = b.user_id
                    AND (
                        (b.category_id IS NOT NULL AND t.category_id = b.category_id)
                        OR (b.category_id IS NULL AND t.category = b.category)
                    )
                WHERE b.user_id = %s AND b.is_active = TRUE
            """
            params: list[Any] = [month_start, ctx.user_id]

            if category:
                query += " AND LOWER(COALESCE(c.name, b.category)) = LOWER(%s)"
                params.append(category)

            query += """
                GROUP BY b.id, b.name, category_name, b.amount_limit, b.period, b.is_active
                ORDER BY category_name ASC
            """

            cursor.execute(query, params)
            results = []
            for row in cursor.fetchall():
                limit_val = float(row["amount_limit"] or 0)
                spent_val = float(row["spent"] or 0)
                pct = round((spent_val / limit_val) * 100, 1) if limit_val > 0 else 0.0
                status = "over_budget" if pct >= 100 else ("warning" if pct >= 80 else "on_track")
                results.append(
                    {
                        "id": row["id"],
                        "name": row["name"],
                        "category": row["category_name"],
                        "limit": limit_val,
                        "spent": spent_val,
                        "remaining": round(limit_val - spent_val, 2),
                        "percent_used": pct,
                        "period": row["period"],
                        "status": status,
                    }
                )
            return results
        finally:
            cursor.close()


def get_spending_summary_v2(
    ctx: BudgetAppContext,
    period: str = "monthly",
) -> dict[str, Any]:
    with get_db_connection(ctx) as conn:
        cursor = conn.cursor(dictionary=True)
        try:
            today = date.today()
            normalized = (period or "monthly").lower()
            if normalized == "weekly":
                start = today - timedelta(days=today.weekday())
            elif normalized == "yearly":
                start = today.replace(month=1, day=1)
            else:
                start = today.replace(day=1)

            cursor.execute(
                """
                SELECT
                    COALESCE(c.name, t.category) AS category_name,
                    t.type,
                    SUM(t.amount) AS total,
                    COUNT(*) AS count
                FROM transactions t
                LEFT JOIN categories c ON t.category_id = c.id
                WHERE t.user_id = %s AND t.transaction_date >= %s
                GROUP BY category_name, t.type
                ORDER BY total DESC
                """,
                (ctx.user_id, start.isoformat()),
            )

            total_income = 0.0
            total_expenses = 0.0
            categories: list[dict[str, Any]] = []
            for row in cursor.fetchall():
                amount = float(row["total"])
                if row["type"] == "income":
                    total_income += amount
                else:
                    total_expenses += amount
                categories.append(
                    {
                        "category": row["category_name"],
                        "type": row["type"],
                        "total": amount,
                        "transaction_count": int(row["count"]),
                    }
                )

            return {
                "period": period,
                "start_date": start.isoformat(),
                "end_date": today.isoformat(),
                "total_income": round(total_income, 2),
                "total_expenses": round(total_expenses, 2),
                "net": round(total_income - total_expenses, 2),
                "breakdown": categories,
            }
        finally:
            cursor.close()

