USE budgettracker_pro;

INSERT INTO users (id, name, email, password)
VALUES (
    1,
    'Demo User',
    'demo@konticodelabs.com',
    '$2y$12$.4k7im0Oh.Xc7SwFrYWG2OxNGGWEf4d7ZpKazo9BdeAlsiICeL9zS'
)
ON DUPLICATE KEY UPDATE
    name = VALUES(name),
    email = VALUES(email),
    password = VALUES(password);

INSERT IGNORE INTO budgets (user_id, category, amount, amount_limit, period, is_active)
VALUES
    (1, 'Groceries', 450.00, 450.00, 'monthly', TRUE),
    (1, 'Dining Out', 180.00, 180.00, 'monthly', TRUE),
    (1, 'Subscriptions', 60.00, 60.00, 'monthly', TRUE),
    (1, 'Transportation', 160.00, 160.00, 'monthly', TRUE);

INSERT INTO transactions (user_id, category, description, amount, type, transaction_date)
VALUES
    (1, 'Salary', 'Main paycheck', 3200.00, 'income', CURDATE() - INTERVAL 18 DAY),
    (1, 'Groceries', 'Walmart weekly groceries', 82.14, 'expense', CURDATE() - INTERVAL 7 DAY),
    (1, 'Dining Out', 'Starbucks', 6.45, 'expense', CURDATE() - INTERVAL 2 DAY),
    (1, 'Dining Out', 'Lunch with friends', 24.90, 'expense', CURDATE() - INTERVAL 3 DAY),
    (1, 'Transportation', 'Gas station', 43.55, 'expense', CURDATE() - INTERVAL 5 DAY),
    (1, 'Subscriptions', 'Netflix', 15.99, 'expense', CURDATE() - INTERVAL 12 DAY),
    (1, 'Subscriptions', 'Spotify', 10.99, 'expense', CURDATE() - INTERVAL 10 DAY),
    (1, 'Shopping', 'Household supplies', 38.20, 'expense', CURDATE() - INTERVAL 4 DAY),
    (1, 'Groceries', 'Trader Joe''s', 56.30, 'expense', CURDATE() - INTERVAL 1 DAY),
    (1, 'Rent', 'Monthly rent', 850.00, 'expense', DATE_FORMAT(CURDATE(), '%Y-%m-01'));

INSERT INTO user_alert_preferences (user_id, alert_type, is_enabled)
VALUES
    (1, 'overspending_risk', TRUE),
    (1, 'forecast', TRUE),
    (1, 'budget_threshold', TRUE),
    (1, 'subscription_review', TRUE),
    (1, 'coach_recommendation', TRUE)
ON DUPLICATE KEY UPDATE
    is_enabled = VALUES(is_enabled);

INSERT INTO user_ai_settings (user_id, coach_score_default_visible, weekly_digest_enabled, notification_cadence)
VALUES (1, TRUE, TRUE, 'weekly')
ON DUPLICATE KEY UPDATE
    coach_score_default_visible = VALUES(coach_score_default_visible),
    weekly_digest_enabled = VALUES(weekly_digest_enabled),
    notification_cadence = VALUES(notification_cadence);
