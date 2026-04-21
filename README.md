# Budget Tracker App by Konticode Labs

A modern PHP + MySQL budget tracker with AI-assisted insights, dashboard alerts, settings controls, and a Python Agent SDK layer for natural-language financial workflows.

## Deployment Structure (Railway)

The app is organized around a simple PHP deployment layout:

```text
Budget_Tracker/
|
|-- public/
|   |-- index.php
|   |-- login.php
|   |-- dashboard.php
|   |-- assets/
|   `-- css/
|
|-- src/
|   |-- auth.php
|   |-- analytics.php
|   |-- transactions.php
|   `-- helpers.php
|
|-- config/
|   `-- database.php
|
|-- sql/
|   `-- schema.sql
|
|-- composer.json
|-- start.sh
|-- Procfile
|-- .gitignore
`-- README.md
```

Notes:

- `public/` is the web root in deployment.
- `start.sh` binds the app to Railway's `PORT` environment variable.
- `Dockerfile` uses `php:8.2-cli` so Apache MPM module conflicts are avoided.

## Highlights

- Dark, dashboard-first budget tracking UI
- Income and expense transaction management
- Budget monitoring with overspending signals
- AI Budget Coach prompts and account-level AI settings
- Dynamic dashboard alerts with dismissals and saved preferences
- Natural-language Quick Add on the transaction page
- Settings page for AI and alert controls
- Agent SDK layer for forecasting, summaries, categories, and richer assistant workflows

## Tech Stack

- PHP
- MySQL
- Tailwind CSS via CDN
- Python Agent SDK integration
- OpenAI Agents SDK when configured

## Product Positioning

This project is structured to work as:

- a personal finance starter app
- a sellable dashboard template
- an AI-enabled SaaS prototype
- a marketplace product for Gumroad or CodeCanyon

## Core Features

See [docs/FEATURES.md](./docs/FEATURES.md) for a full product feature list.

## Installation

### 1. Configure the database

Copy:

- [config/database.local.php.example](./config/database.local.php.example)

to:

- `config/database.local.php`

Then update your local database credentials.

### 2. Create the schema

For the current recommended setup, import:

- [sql/schema_v2_compatible.sql](./sql/schema_v2_compatible.sql)

Optional:

- [sql/demo_seed.sql](./sql/demo_seed.sql) to load demo data

### 3. Start the PHP app

Point your local PHP server, Apache, or XAMPP/Laragon setup at:

- `BudgetTracker-Pro/public`

### 4. Optional AI Agent SDK setup

Copy:

- [agent_sdk/.env.example](./agent_sdk/.env.example)

to:

- `agent_sdk/.env`

Install Python dependencies:

```bash
pip install openai-agents mysql-connector-python pandas python-dotenv
```

Agent SDK details:

- [agent_sdk/README.md](./agent_sdk/README.md)

## Railway Deployment

### Runtime

- Docker-based deploy uses [Dockerfile](./Dockerfile)
- App binds to Railway `PORT` via [start.sh](./start.sh)
- Public web root is `public/`

### Database Environment Variables

The app supports either custom `BT_DB_*` variables or Railway MySQL variables.

Priority order used by the app:

1. `BT_DB_HOST` then `MYSQLHOST`
2. `BT_DB_USER` then `MYSQLUSER`
3. `BT_DB_PASSWORD` then `MYSQLPASSWORD`
4. `BT_DB_NAME` then `MYSQLDATABASE`
5. `BT_DB_PORT` then `MYSQLPORT` (fallback `3306`)

### First Deploy Checklist

1. Push your code to GitHub.
2. Create a Railway project and connect the repository.
3. Ensure Railway builds using the repository Dockerfile.
4. Attach a Railway MySQL service or manually set `BT_DB_*` variables.
5. Import one schema file into your database:
	- Recommended: [sql/schema_v2_compatible.sql](./sql/schema_v2_compatible.sql)
	- Legacy/basic: [sql/schema.sql](./sql/schema.sql)
6. Redeploy and open `/`.

### Troubleshooting

- If startup fails with a port issue, confirm Railway detects `PORT` and that `start.sh` is present.
- If DB connection fails, validate env var names and database host reachability from Railway.
- If login/dashboard fails after DB connects, confirm schema import completed successfully.

## Demo Login

If you load [sql/demo_seed.sql](./sql/demo_seed.sql), use:

- email: `demo@konticodelabs.com`
- password: `demo1234`

## Included Product Assets

- [CHANGELOG.md](./CHANGELOG.md)
- [LICENSE.md](./LICENSE.md)
- [docs/INSTALL.md](./docs/INSTALL.md)
- [docs/MARKETING_COPY.md](./docs/MARKETING_COPY.md)
- [docs/SCREENSHOT_PLAN.md](./docs/SCREENSHOT_PLAN.md)
- [docs/PACKAGING_GUIDE.md](./docs/PACKAGING_GUIDE.md)
- [docs/DEMO_DATA.md](./docs/DEMO_DATA.md)
- [marketplace/README.md](./marketplace/README.md)

## Version

Current release target:

- `v1.0.0`

## Notes For Sellers

This repo now includes the basic product structure needed for marketplace prep, but you should still review:

- branding
- licensing terms
- support terms
- screenshot exports
- demo hosting

before selling publicly.
