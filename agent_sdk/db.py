from __future__ import annotations

from contextlib import contextmanager

import mysql.connector

from .context import BudgetAppContext


@contextmanager
def get_db_connection(ctx: BudgetAppContext):
    connection = mysql.connector.connect(
        host=ctx.db_host,
        user=ctx.db_user,
        password=ctx.db_password,
        database=ctx.db_name,
    )
    try:
        yield connection
    finally:
        connection.close()
