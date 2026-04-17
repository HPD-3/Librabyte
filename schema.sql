CREATE DATABASE IF NOT EXISTS library_system CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE library_system;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    bio VARCHAR(250) NOT NULL DEFAULT 'No bio yet.',
    email VARCHAR(50) NOT NULL,
    image BLOB NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS favorites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    book_id VARCHAR(100) NOT NULL,
    title VARCHAR(255) NOT NULL,
    authors TEXT,
    thumbnail TEXT,
    rating FLOAT,
    UNIQUE KEY unique_user_book (user_id, book_id),
    CONSTRAINT fk_favorites_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS website_reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reviewer_name VARCHAR(255) NOT NULL,
    rating INT NOT NULL,
    review TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);