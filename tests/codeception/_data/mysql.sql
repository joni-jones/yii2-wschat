DROP TABLE IF EXISTS history;

CREATE TABLE history(
    id UNSIGNED INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
    chat_id VARCHAR(60),
    chat_title VARCHAR(60),
    user_id VARCHAR(60),
    username VARCHAR(60),
    avatar_16 VARCHAR(90),
    avatar_32 VARCHAR(90),
    timestamp UNSIGNED INTEGER NOT NULL DEFAULT 0,
    message TEXT
);
