-- Users Table --
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    is_admin TINYINT(1) DEFAULT 0,
    profile_image VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    auth_token VARCHAR(255) DEFAULT NULL,
    token_expiry DATETIME DEFAULT NULL
);

-- Default Admin User --
INSERT INTO users (first_name, last_name, email, password, is_admin) 
VALUES (
    'Admin', 
    'User', 
    'admin@example.com', 
    '$2y$10$6/mFp92UlSXrBi7DQZs3Cut0WZaj2EX.c8W4cK5KWhZlz34f4vJOW', -- This is 'admin123'
    1
);

-- Skills Table --
CREATE TABLE skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Skills Insert Query
INSERT INTO skills (name) VALUES 
('Logical Thinking'),
('Programming & Coding'),
('Database & SQL'),
('Analytical & Problem-Solving'),
('Language & Communication'),
('Quantitative & Aptitude'),
('Observation & Attention'),
('Speed & Reaction'),
('String & Pattern Analysis');

-- Users Table has_skills column
ALTER TABLE users 
ADD COLUMN has_skills TINYINT(1) DEFAULT 0 AFTER token_expiry;

-- User Skills Table
CREATE TABLE user_skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    skill_id INT NOT NULL,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (skill_id) REFERENCES skills(id) ON DELETE CASCADE
);