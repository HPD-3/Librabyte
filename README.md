# Library System Database Setup

This repository contains SQL code for creating a `library_system` database with the following tables:
- `users` table: Stores information about users.
- `favorites` table: Stores user-favorite books.
- `website_reviews` table: Stores reviews left by users.

## Prerequisites

1. **XAMPP** installed on your machine (make sure MySQL is running).
2. **MySQL Workbench** or another MySQL client (optional, for easier execution).

## Steps to Run the SQL Code

### 1. Install XAMPP
If you haven't already installed XAMPP, download and install it from the [official website](https://www.apachefriends.org/).

### 2. Start XAMPP
1. Open the **XAMPP Control Panel**.
2. Click on **Start** next to **MySQL** to start the MySQL service.

### 3. Access MySQL via phpMyAdmin
1. Open your browser and navigate to [http://localhost/phpmyadmin](http://localhost/phpmyadmin).
2. Login with the default username `root` (no password).

### 4. Create the Database
You can either run the SQL script using phpMyAdmin or directly from the MySQL command line.

#### Using phpMyAdmin:
1. In phpMyAdmin, click on the **Databases** tab.
2. In the **Create database** section, enter `library_system` as the database name.
3. Select the collation as `utf8mb4_general_ci` (or leave the default collation).
4. Click **Create** to create the database.

#### Using MySQL Command Line:
If you prefer to run SQL from the command line:
1. Open the **XAMPP Control Panel** and click **Shell** to open the terminal.
2. Type the following commands:
   
   ```bash
   CREATE DATABASE library_system;
   USE library_system;

   CREATE TABLE users (
     id INT AUTO_INCREMENT PRIMARY KEY,
     username VARCHAR(50) NOT NULL UNIQUE,
     password VARCHAR(255) NOT NULL,
     bio VARCHAR(250) NOT NULL,
     email VARCHAR(50) NOT NULL,
     image BLOB NOT NULL
   );

   CREATE TABLE favorites (
     id INT AUTO_INCREMENT PRIMARY KEY,
     user_id INT NOT NULL,
     book_id VARCHAR(100) NOT NULL,
     title VARCHAR(255),
     authors TEXT,
     thumbnail TEXT,
     rating FLOAT,
     UNIQUE(user_id, book_id)
   );

   CREATE TABLE website_reviews (
       id INT AUTO_INCREMENT PRIMARY KEY,
       reviewer_name VARCHAR(255) NOT NULL,  -- Name of the reviewer
       rating INT NOT NULL,  -- Rating from 1 to 5 stars
       review TEXT,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
   );

  ## Steps to Run the PHP App Locally

  This project is a plain PHP app, so it should be run with Apache/Laragon/XAMPP or the built-in PHP server.

  ### Option 1: Laragon or XAMPP
  1. Put the project in your web root.
  2. Start Apache and MySQL.
  3. Open the app in your browser.

  For Laragon, the URL is usually:

  ```text
  http://localhost/libraBtye-main/
  ```

  ### Option 2: PHP built-in server
  Run this from the project root:

  ```powershell
  php -S localhost:8000 -t .
  ```

  Then open:

  ```text
  http://localhost:8000/
  ```

  Important:
  - Do not run `php -S localhost:8000 -t` by itself.
  - The `-t` option must always be followed by a folder path.
"# Librabyte" 
