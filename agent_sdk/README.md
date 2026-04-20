# Budget Tracker App Agent SDK

This folder adds a Python-based multi-agent layer next to the PHP application.

Architecture:

- `Budget Coordinator Agent`
  - routes user requests to specialist agents
- `Transaction Agent`
  - transaction CRUD and recent transaction lookups
- `Budget Agent`
  - budget CRUD and budget summaries
- `Analytics Agent`
  - trend analysis using MySQL data and optional `pandas`
- `Category Agent`
  - category discovery and category rename operations across records
- `Insights Agent`
  - finance summaries and coaching-style reasoning over user data

Suggested package install:

```bash
pip install openai-agents mysql-connector-python pandas python-dotenv
```

Set environment variables before running:

```bash
OPENAI_API_KEY=your_key
BT_DB_HOST=localhost
BT_DB_USER=root
BT_DB_PASSWORD=test123#
BT_DB_NAME=budgettracker_pro
BT_USER_ID=1
```

You can also place these in a local `.env` file inside `BudgetTracker-Pro/agent_sdk/` or the project root.

Run:

```bash
python -m agent_sdk.main "Show me my biggest spending category this month"
```

JSON mode:

```bash
python -m agent_sdk.main --json "How much have I spent this month?"
```

Notes:

- The current PHP schema does not include a dedicated `categories` table, so the Category Agent manages categories by reading and updating category values in `transactions` and `budgets`.
- The Insights Agent uses GPT reasoning through the Agents SDK plus finance context from your database.
- The Analytics Agent prefers `pandas` when available and falls back to SQL/Python summaries when it is not.

Additional repository layer:

- `repository_v2.py`
  - a richer data-access layer designed for `sql/schema_v2_compatible.sql`
  - keeps `user_id` ownership while supporting `categories`, `category_id`, `transaction_date`, and richer budget fields
  - can be adopted gradually by future tools or agents without replacing the current `db.py`

Canonical entrypoint:

- `agent.py`
  - exposes the single app-wide root agent
  - `main.py` and the PHP bridge both go through this path now
  - keeps the coordinator/handoff system as the primary runtime path
