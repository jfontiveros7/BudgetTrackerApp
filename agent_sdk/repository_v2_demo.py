from __future__ import annotations

import json

from .context import BudgetAppContext
from .repository_v2 import (
    get_budget_status_v2,
    get_spending_summary_v2,
    list_categories,
)


def main() -> None:
    ctx = BudgetAppContext.from_env()
    payload = {
        "categories": list_categories(ctx),
        "budget_status": get_budget_status_v2(ctx),
        "spending_summary": get_spending_summary_v2(ctx),
    }
    print(json.dumps(payload, default=str))


if __name__ == "__main__":
    main()
