# Installation Guide

## Requirements

- PHP 8.0+
- MySQL 8+ or MariaDB-compatible database
- Local web server such as Apache, XAMPP, Laragon, or the PHP built-in server
- Python 3.10+ if you want the optional Agent SDK features

## 1. Copy the project

Place the project in your local web directory or workspace.

Recommended public web root:

- `BudgetTracker-Pro/public`

## 2. Configure the database

Copy:

- [config/database.local.php.example](../config/database.local.php.example)

to:

- `config/database.local.php`

Then update:

- host
- user
- password
- database name

## 3. Import the database schema

Recommended:

- [sql/schema_v2_compatible.sql](../sql/schema_v2_compatible.sql)

Optional demo content:

- [sql/demo_seed.sql](../sql/demo_seed.sql)

If you are upgrading an older install, review:

- [sql/migrate_v1_to_v2_compatible.sql](../sql/migrate_v1_to_v2_compatible.sql)

## 4. Start the PHP app

Point your local server to:

- `BudgetTracker-Pro/public`

Or use PHP's built-in server:

```bash
cd BudgetTracker-Pro/public
php -S localhost:8000
```

Then open:

- `http://localhost:8000`

## 5. Optional Agent SDK setup

Copy:

- [agent_sdk/.env.example](../agent_sdk/.env.example)

to:

- `agent_sdk/.env`

Fill in:

- `OPENAI_API_KEY`
- database values
- target `BT_USER_ID`

Install dependencies:

```bash
pip install openai-agents mysql-connector-python pandas python-dotenv
```

Run a test prompt:

```bash
cd BudgetTracker-Pro
python -m agent_sdk.main "How much have I spent this month?"
```

## Demo Account

If you imported [sql/demo_seed.sql](../sql/demo_seed.sql), log in with:

- email: `demo@konticodelabs.com`
- password: `demo1234`

## Troubleshooting

### Database connection failed

- verify `config/database.local.php`
- verify MySQL is running
- verify the database exists

### Agent SDK falls back to local mode

- confirm `OPENAI_API_KEY` is set
- confirm network access is available
- confirm Python dependencies are installed

### Alerts or settings tables are missing

The app can create some preference tables at runtime, but importing the latest schema is still recommended for clean installs.
