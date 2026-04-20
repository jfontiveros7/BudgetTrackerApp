-- BudgetTracker Pro
-- In-place migration from the original schema to the v2-compatible schema.
-- This script is additive and preserves existing user, budget, and transaction data.
--
-- Expected starting point:
--   - users
--   - budgets(user_id, category, amount, created_at)
--   - transactions(user_id, category, description, amount, type, created_at)
--
-- Target outcome:
--   - categories table added
--   - budgets gains category_id, amount_limit, period, is_active
--   - transactions gains category_id, transaction_date
--   - existing text categories preserved
--   - existing rows backfilled where possible

USE budgettracker_pro;

CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    type ENUM('income', 'expense') NOT NULL DEFAULT 'expense',
    icon VARCHAR(50) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

DELIMITER $$

DROP PROCEDURE IF EXISTS migrate_budgettracker_v1_to_v2 $$
CREATE PROCEDURE migrate_budgettracker_v1_to_v2()
BEGIN
    IF NOT EXISTS (
        SELECT 1
        FROM information_schema.columns
        WHERE table_schema = DATABASE()
          AND table_name = 'transactions'
          AND column_name = 'transaction_date'
    ) THEN
        ALTER TABLE transactions
            ADD COLUMN transaction_date DATE NULL AFTER type;
    END IF;

    IF NOT EXISTS (
        SELECT 1
        FROM information_schema.columns
        WHERE table_schema = DATABASE()
          AND table_name = 'transactions'
          AND column_name = 'category_id'
    ) THEN
        ALTER TABLE transactions
            ADD COLUMN category_id INT NULL AFTER category;
    END IF;

    IF NOT EXISTS (
        SELECT 1
        FROM information_schema.columns
        WHERE table_schema = DATABASE()
          AND table_name = 'budgets'
          AND column_name = 'category_id'
    ) THEN
        ALTER TABLE budgets
            ADD COLUMN category_id INT NULL AFTER category;
    END IF;

    IF NOT EXISTS (
        SELECT 1
        FROM information_schema.columns
        WHERE table_schema = DATABASE()
          AND table_name = 'budgets'
          AND column_name = 'amount_limit'
    ) THEN
        ALTER TABLE budgets
            ADD COLUMN amount_limit DECIMAL(12,2) NULL AFTER amount;
    END IF;

    IF NOT EXISTS (
        SELECT 1
        FROM information_schema.columns
        WHERE table_schema = DATABASE()
          AND table_name = 'budgets'
          AND column_name = 'period'
    ) THEN
        ALTER TABLE budgets
            ADD COLUMN period ENUM('weekly', 'biweekly', 'monthly', 'yearly') NOT NULL DEFAULT 'monthly' AFTER amount_limit;
    END IF;

    IF NOT EXISTS (
        SELECT 1
        FROM information_schema.columns
        WHERE table_schema = DATABASE()
          AND table_name = 'budgets'
          AND column_name = 'is_active'
    ) THEN
        ALTER TABLE budgets
            ADD COLUMN is_active BOOLEAN NOT NULL DEFAULT TRUE AFTER period;
    END IF;
END $$

CALL migrate_budgettracker_v1_to_v2() $$
DROP PROCEDURE IF EXISTS migrate_budgettracker_v1_to_v2 $$

DELIMITER ;

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

-- Bring over any categories already used in transactions and budgets
INSERT IGNORE INTO categories (name, type)
SELECT DISTINCT t.category, COALESCE(t.type, 'expense')
FROM transactions t
WHERE t.category IS NOT NULL AND TRIM(t.category) <> '';

INSERT IGNORE INTO categories (name, type)
SELECT DISTINCT b.category, 'expense'
FROM budgets b
WHERE b.category IS NOT NULL AND TRIM(b.category) <> '';

-- Backfill transaction_date from created_at where needed
UPDATE transactions
SET transaction_date = DATE(created_at)
WHERE transaction_date IS NULL;

-- Backfill amount_limit from amount where needed
UPDATE budgets
SET amount_limit = amount
WHERE amount_limit IS NULL AND amount IS NOT NULL;

-- Backfill category_id from text category where needed
UPDATE transactions t
JOIN categories c ON LOWER(c.name) = LOWER(t.category)
SET t.category_id = c.id
WHERE t.category_id IS NULL
  AND t.category IS NOT NULL
  AND TRIM(t.category) <> '';

UPDATE budgets b
JOIN categories c ON LOWER(c.name) = LOWER(b.category)
SET b.category_id = c.id
WHERE b.category_id IS NULL
  AND b.category IS NOT NULL
  AND TRIM(b.category) <> '';

DELIMITER $$

DROP PROCEDURE IF EXISTS add_budgettracker_v2_constraints_and_indexes $$
CREATE PROCEDURE add_budgettracker_v2_constraints_and_indexes()
BEGIN
    IF NOT EXISTS (
        SELECT 1
        FROM information_schema.table_constraints
        WHERE table_schema = DATABASE()
          AND table_name = 'transactions'
          AND constraint_name = 'fk_transactions_category_v2'
    ) THEN
        ALTER TABLE transactions
            ADD CONSTRAINT fk_transactions_category_v2
            FOREIGN KEY (category_id) REFERENCES categories(id)
            ON DELETE SET NULL;
    END IF;

    IF NOT EXISTS (
        SELECT 1
        FROM information_schema.table_constraints
        WHERE table_schema = DATABASE()
          AND table_name = 'budgets'
          AND constraint_name = 'fk_budgets_category_v2'
    ) THEN
        ALTER TABLE budgets
            ADD CONSTRAINT fk_budgets_category_v2
            FOREIGN KEY (category_id) REFERENCES categories(id)
            ON DELETE SET NULL;
    END IF;

    IF NOT EXISTS (
        SELECT 1
        FROM information_schema.statistics
        WHERE table_schema = DATABASE()
          AND table_name = 'transactions'
          AND index_name = 'idx_transactions_user_date'
    ) THEN
        CREATE INDEX idx_transactions_user_date ON transactions(user_id, transaction_date);
    END IF;

    IF NOT EXISTS (
        SELECT 1
        FROM information_schema.statistics
        WHERE table_schema = DATABASE()
          AND table_name = 'transactions'
          AND index_name = 'idx_transactions_type'
    ) THEN
        CREATE INDEX idx_transactions_type ON transactions(type);
    END IF;

    IF NOT EXISTS (
        SELECT 1
        FROM information_schema.statistics
        WHERE table_schema = DATABASE()
          AND table_name = 'transactions'
          AND index_name = 'idx_transactions_category_id'
    ) THEN
        CREATE INDEX idx_transactions_category_id ON transactions(category_id);
    END IF;

    IF NOT EXISTS (
        SELECT 1
        FROM information_schema.statistics
        WHERE table_schema = DATABASE()
          AND table_name = 'transactions'
          AND index_name = 'idx_transactions_category_text'
    ) THEN
        CREATE INDEX idx_transactions_category_text ON transactions(category);
    END IF;

    IF NOT EXISTS (
        SELECT 1
        FROM information_schema.statistics
        WHERE table_schema = DATABASE()
          AND table_name = 'budgets'
          AND index_name = 'idx_budgets_user_category'
    ) THEN
        CREATE INDEX idx_budgets_user_category ON budgets(user_id, category);
    END IF;

    IF NOT EXISTS (
        SELECT 1
        FROM information_schema.statistics
        WHERE table_schema = DATABASE()
          AND table_name = 'budgets'
          AND index_name = 'idx_budgets_category_id'
    ) THEN
        CREATE INDEX idx_budgets_category_id ON budgets(category_id);
    END IF;
END $$

CALL add_budgettracker_v2_constraints_and_indexes() $$
DROP PROCEDURE IF EXISTS add_budgettracker_v2_constraints_and_indexes $$

DELIMITER ;

-- Optional cleanup: make transaction_date non-null after backfill
-- Uncomment if your MySQL version and data are ready for strict enforcement:
-- ALTER TABLE transactions MODIFY transaction_date DATE NOT NULL DEFAULT (CURRENT_DATE);
