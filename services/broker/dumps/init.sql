CREATE TABLE IF NOT EXISTS request (
    token VARCHAR ( 255 ) PRIMARY KEY,
    message VARCHAR ( 255 ) NOT NULL,
    is_ready BOOLEAN NOT NULL DEFAULT false,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);