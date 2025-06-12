-- Check if rejection_reason column exists in album table
SET @columnExists = 0;
SELECT COUNT(*) INTO @columnExists 
FROM information_schema.COLUMNS 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'album' 
AND COLUMN_NAME = 'rejection_reason';

-- Add the column if it doesn't exist
SET @query = IF(@columnExists = 0, 
    'ALTER TABLE album ADD COLUMN rejection_reason TEXT NULL COMMENT "Reason for rejection when status is 2"', 
    'SELECT "Column already exists"');

PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;