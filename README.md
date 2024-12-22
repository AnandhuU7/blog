# Blog System

This is a simple blog system where users can register, log in, create posts, edit posts, and comment on posts. The system uses PHP and MySQL to store and manage user data and blog posts. This README provides information on how to set up and run the project.

## Features

- **User Authentication**: Users can register, log in, and log out.
- **Post Creation**: Users can create new posts, including optional images.
- **Post Management**: Users can edit or delete their posts.
- **Comments & Likes**: Users can comment on posts and like them.

## Files

1. **`index.php`**: Displays all posts except the logged-in user's posts, with options to like and comment on posts.
2. **`register.php`**: Handles user registration.
3. **`login.php`**: Handles user login.
4. **`create_post.php`**: Allows users to create a new post.
5. **`edit_post.php`**: Allows users to edit their posts.
6. **`my_posts.php`**: Displays the posts of the logged-in user.
7. **`like_comment.php`**: Handles liking and commenting on posts.
8. **`config.php`**: Contains the database connection information.

## Prerequisites

1. PHP (version 7.4 or above)
2. MySQL
3. Apache or any other PHP server
4. Bootstrap (for frontend design)

## Database Setup

Create a database called `blog_system` in MySQL with the following tables:

### 1. `users`
Stores user information for login and registration.

```sql
CREATE TABLE `users` (
  `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL
);

CREATE TABLE `posts` (
  `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `content` TEXT NOT NULL,
  `user_id` INT(11) NOT NULL,
  `image` VARCHAR(255) DEFAULT NULL,
  `created_at` DATETIME NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
);

CREATE TABLE `comments` (
  `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
  `post_id` INT(11) NOT NULL,
  `user_id` INT(11) NOT NULL,
  `content` TEXT NOT NULL,
  `created_at` DATETIME NOT NULL,
  FOREIGN KEY (`post_id`) REFERENCES `posts`(`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
);

CREATE TABLE `likes` (
  `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
  `post_id` INT(11) NOT NULL,
  `user_id` INT(11) NOT NULL,
  FOREIGN KEY (`post_id`) REFERENCES `posts`(`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
);
