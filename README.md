# Budget Tracker App by Konticode Labs

A modern PHP + MySQL budget tracker with AI-assisted insights, dashboard alerts, settings controls, and a Python Agent SDK layer for natural-language financial workflows.

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
