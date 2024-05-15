-- Create database if not exists
CREATE DATABASE IF NOT EXISTS your_database;

-- Use the created database
USE your_database;

-- Create table to store spam messages
CREATE TABLE IF NOT EXISTS spam_reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create Links Table
CREATE TABLE IF NOT EXISTS links(
    link_id INT AUTO_INCREMENT PRIMARY KEY,
    url VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create Users Table
CREATE TABLE IF NOT EXISTS users(
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    
);


-- Create Link-User Table
CREATE TABLE IF NOT EXISTS link_user(
    link_user_id INT AUTO_INCREMENT PRIMARY KEY,
    link_id INT,
    user_id INT,
    access_token VARCHAR(32),
    FOREIGN KEY(link_id) REFERENCES links(link_id),
    FOREIGN KEY(user_id) REFERENCES users(user_id)
);
ALTER TABLE users ADD COLUMN password VARCHAR(255) NOT NULL;

-- Create a new database
CREATE DATABASE IF NOT EXISTS your_new_database;

-- Switch to the newly created database
USE your_new_database;

-- Create the users table
CREATE TABLE IF NOT EXISTS users (
  id INT(11) NOT NULL AUTO_INCREMENT,
  firstname VARCHAR(100) NOT NULL,
  lastname VARCHAR(100) NOT NULL,
  email VARCHAR(50) NOT NULL,
  mobilenumber VARCHAR(50) NOT NULL,
  password VARCHAR(255) NOT NULL,
  token VARCHAR(255) NOT NULL,
  is_active ENUM('0','1') NOT NULL DEFAULT '0',
  date_time DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY email_unique (email) -- Ensure email uniqueness
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE users ADD COLUMN activation_token VARCHAR(255) DEFAULT NULL;


CREATE TABLE `shortened_urls` (
  `id` int(10) NOT NULL,
  `long_url` longtext NOT NULL,
  `short_url` varchar(50) NOT NULL
) 


INSERT INTO `shortened_urls` (`id`, `long_url`, `short_url`) VALUES
(1, 'https://www.amazon.com/Garmin-Smartwatch-Touchscreen-Monitoring-010-02173-11/dp/B07WLN9RYD?pf_rd_p=d22f02ec-561d-470e-9f56-891084a0600d&pd_rd_wg=Qo0Ro&pf_rd_r=019J72NE9TMCM3S11YXZ&ref_=pd_gw_unk&pd_rd_w=MWw7Q&pd_rd_r=8f8fe597-657d-46db-a9db-a43f22af852a', 'yglih');


ALTER TABLE `shortened_urls`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `shortened_urls`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

