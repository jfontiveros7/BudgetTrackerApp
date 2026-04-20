## What changed

This PR makes the Budget Tracker app more proactive and workflow-aware across the dashboard, settings, and Agent SDK.

- improved Agent SDK fallback behavior and error visibility
- added richer agent tools for forecast, top categories, subscriptions, and overspending risk
- added dynamic dashboard alerts driven by budget, forecast, and spending signals
- added dismissible alerts with account-level preferences
- added a dedicated `Settings` page for `AI & Alerts`
- added account-level AI settings, including default Coach Score visibility
- updated schema files for alert preferences, dismissals, and AI settings

## Why

The app already had useful insights, but it was still mostly reactive. These changes make it feel more intelligent and user-aware by:

- surfacing important alerts automatically
- letting users control alert noise
- persisting AI and alert preferences across devices
- making the dashboard adapt to how each user wants to work

## User impact

Users can now:

- see live alerts on the dashboard
- dismiss alerts and restore them later
- choose which alert categories appear
- manage AI and alert behavior from a dedicated settings page
- decide whether the Coach Score is shown by default

## Validation

- `php -l public/dashboard.php`
- `php -l public/settings.php`
- `php -l public/admin.php`
- `php -l public/add_transaction.php`
- `php -l public/api/alert_preferences.php`
- `php -l src/agent.php`
- `php -l src/alert_preferences.php`

## Notes

`weekly digest` and `notification cadence` settings are now stored and ready for future proactive features. Account-level Coach Score visibility is active now.
