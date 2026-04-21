-- BudgetTracker Pro v2 Compatible Schema
-- This schema keeps the current app working while adding richer category-based fields.
-- It preserves:
--   - users table for authentication
--   - user_id on budgets and transactions
--   - category text fields used by the current PHP app
-- It adds:
--   - categories table
--   - optional category_id foreign keys
--   - transaction_date for reporting
--   - amount_limit, period, and is_active for budgets

CREATE DATABASE IF NOT EXISTS budgettracker_pro;
USE budgettracker_pro;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(150) UNIQUE,
    password VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    type ENUM('income', 'expense') NOT NULL DEFAULT 'expense',
    icon VARCHAR(50) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS budgets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    category VARCHAR(100),
    category_id INT DEFAULT NULL,
    amount DECIMAL(10,2),
    amount_limit DECIMAL(12,2) DEFAULT NULL,
    period ENUM('weekly', 'biweekly', 'monthly', 'yearly') NOT NULL DEFAULT 'monthly',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_budgets_user_v2
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_budgets_category_v2
        FOREIGN KEY (category_id) REFERENCES categories(id)
        ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    category VARCHAR(100),
    category_id INT DEFAULT NULL,
    description VARCHAR(255),
    amount DECIMAL(10,2),
    type ENUM('income','expense'),
    transaction_date DATE NOT NULL DEFAULT (CURRENT_DATE),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_transactions_user_v2
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_transactions_category_v2
        FOREIGN KEY (category_id) REFERENCES categories(id)
        ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS user_alert_preferences (
    user_id INT NOT NULL,
    alert_type VARCHAR(100) NOT NULL,
    is_enabled BOOLEAN NOT NULL DEFAULT TRUE,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, alert_type),
    CONSTRAINT fk_user_alert_preferences_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS user_alert_dismissals (
    user_id INT NOT NULL,
    alert_id VARCHAR(64) NOT NULL,
    dismissed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, alert_id),
    CONSTRAINT fk_user_alert_dismissals_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS user_ai_settings (
    user_id INT NOT NULL PRIMARY KEY,
    coach_score_default_visible BOOLEAN NOT NULL DEFAULT FALSE,
    weekly_digest_enabled BOOLEAN NOT NULL DEFAULT TRUE,
    notification_cadence VARCHAR(30) NOT NULL DEFAULT 'weekly',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_user_ai_settings_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
);

    CREATE TABLE IF NOT EXISTS password_resets (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        token_hash VARCHAR(64) NOT NULL UNIQUE,
        expires_at DATETIME NOT NULL,
        used_at DATETIME DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        CONSTRAINT fk_password_resets_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
    );

-- Seed default categories
INSERT IGNORE INTO categories (name, type) VALUES
    ('Salary', 'income'),
    ('Freelance', 'income'),
    ('Investments', 'income'),
    ('Side Hustle', 'income'),
    ('Groceries', 'expense'),
    ('Rent', 'expense'),
    ('Utilities', 'expense'),
    ('Dining Out', 'expense'),
    ('Transportation', 'expense'),
    ('Entertainment', 'expense'),
    ('Shopping', 'expense'),
    ('Healthcare', 'expense'),
    ('Subscriptions', 'expense'),
    ('Education', 'expense'),
    ('Savings', 'expense'),
    ('Miscellaneous', 'expense');

-- Helpful indexes
CREATE INDEX idx_transactions_user_date ON transactions(user_id, transaction_date);
CREATE INDEX idx_transactions_type ON transactions(type);
CREATE INDEX idx_transactions_category_id ON transactions(category_id);
CREATE INDEX idx_transactions_category_text ON transactions(category);
CREATE INDEX idx_budgets_user_category ON budgets(user_id, category);
CREATE INDEX idx_budgets_category_id ON budgets(category_id);
CREATE INDEX idx_user_alert_preferences_user ON user_alert_preferences(user_id);
CREATE INDEX idx_user_alert_dismissals_user ON user_alert_dismissals(user_id);
CREATE INDEX idx_user_ai_settings_user ON user_ai_settings(user_id);
CREATE INDEX idx_password_resets_user_id ON password_resets(user_id);
CREATE INDEX idx_password_resets_expires_at ON password_resets(expires_at);
