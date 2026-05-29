CREATE DATABASE IF NOT EXISTS goal_tracker_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE goal_tracker_db;

DROP TABLE IF EXISTS goals;

CREATE TABLE goals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    category VARCHAR(50) NOT NULL DEFAULT 'Personal',
    term ENUM('Short Term', 'Medium Term', 'Long Term') NOT NULL DEFAULT 'Short Term',
    status ENUM('Not Started', 'In Progress', 'Completed') NOT NULL DEFAULT 'Not Started',
    notes TEXT NULL,
    due_date DATE NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_goals_category (category),
    INDEX idx_goals_term (term),
    INDEX idx_goals_status (status),
    INDEX idx_goals_due_date (due_date)
);

INSERT INTO goals (title, category, term, status, notes, due_date)
VALUES
    (
        'Review PHP CRUD API',
        'School',
        'Short Term',
        'In Progress',
        'Test every endpoint in Postman before presentation.',
        '2026-06-10'
    ),
    (
        'Organize study schedule',
        'Personal',
        'Medium Term',
        'Not Started',
        'Use the category filter to separate school and personal goals.',
        '2026-07-01'
    );
