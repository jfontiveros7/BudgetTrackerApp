from __future__ import annotations

import os
from dataclasses import dataclass

from dotenv import load_dotenv


load_dotenv()


@dataclass
class BudgetAppContext:
    db_host: str
    db_user: str
    db_password: str
    db_name: str
    user_id: int

    @classmethod
    def from_env(cls) -> "BudgetAppContext":
        return cls(
            db_host=os.getenv("BT_DB_HOST", "localhost"),
            db_user=os.getenv("BT_DB_USER", "root"),
            db_password=os.getenv("BT_DB_PASSWORD", "test123#"),
            db_name=os.getenv("BT_DB_NAME", "budgettracker_pro"),
            user_id=int(os.getenv("BT_USER_ID", "1")),
        )
