-- Database: greenheld_db (Example name, user can change)

--
-- Table structure for table `users`
--
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `projects`
--
CREATE TABLE `projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `outcome` text DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `testimonials`
--
CREATE TABLE `testimonials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quote` text NOT NULL,
  `client_name` varchar(100) NOT NULL,
  `client_title_company` varchar(150) DEFAULT NULL,
  `client_photo_url` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `team_members` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`name` varchar(100) NOT NULL,
`role` varchar(100) NOT NULL,
`bio` text,
`photo_url` varchar(255),
`created_at` datetime DEFAULT CURRENT_TIMESTAMP,
`updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Instructions for setting up the initial admin user:
-- 1. Connect to your MySQL database.
-- 2. Execute the content of this schema.sql file to create the tables.
-- 3. To create an initial admin user, run the following SQL query.
--    Choose a strong password and replace 'your_secure_password' before running.
--    The password will be hashed by PHP when you first set it up or via a separate script.
--    For now, this is a placeholder to create the user row.
--    A more secure way is to have a script to add the first user.

-- Example of inserting a user (password should be hashed by a PHP script, not inserted as plain text here):
-- For demonstration, let's assume you have a PHP script to generate a hash:
-- <?php echo password_hash('admin_password', PASSWORD_DEFAULT); ?>
-- Then take the output of that and use it in your INSERT statement.
--
-- INSERT INTO `users` (`username`, `password`) VALUES
-- ('admin', '$2y$10$...'); -- Replace $2y$10$... with the actual hash

-- Alternatively, a setup script (PHP) could prompt for admin details and insert them correctly hashed.
-- For the purpose of this phase, we'll assume the user can manually insert a hashed password
-- or we will provide a small utility script later if needed for user creation.

-- Adding updated_at columns to projects and testimonials for better tracking.
-- Using utf8mb4 for better character support.
-- Using InnoDB engine by default.
-- Default CURRENT_TIMESTAMP for created_at and ON UPDATE CURRENT_TIMESTAMP for updated_at are useful.
-- Unique key for username in users table.
