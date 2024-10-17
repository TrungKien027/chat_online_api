CREATE TABLE `chat_rooms` (
  `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `user_id_1` INT NOT NULL,
  `user_id_2` INT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT (CURRENT_TIMESTAMP)
);

CREATE TABLE `posts` (
  `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `content` TEXT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT (CURRENT_TIMESTAMP),
  `updated_at` TIMESTAMP NOT NULL
);

CREATE TABLE `notifications` (
  `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `content` VARCHAR(255) NOT NULL,
  `type` ENUM ('friend_request', 'message', 'comment', 'like', 'system') NOT NULL,
  `is_read` INT NOT NULL DEFAULT '0',
  `created_at` TIMESTAMP NOT NULL DEFAULT (CURRENT_TIMESTAMP)
);

CREATE TABLE `roles` (
  `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `role_name` VARCHAR(255) NOT NULL
);

CREATE TABLE `user_info` (
  `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `age` INT,
  `gender` ENUM ('male', 'female', 'other'),
  `phone` VARCHAR(255),
  `created_at` TIMESTAMP NOT NULL DEFAULT (CURRENT_TIMESTAMP),
  `updated_at` TIMESTAMP NOT NULL
);

CREATE TABLE `media` (
  `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `post_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `url` VARCHAR(255) NOT NULL,
  `is_avatar` INT NOT NULL DEFAULT '0',
  `media_type` ENUM ('video', 'image', 'audio') NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT (CURRENT_TIMESTAMP)
);

CREATE TABLE `friendships` (
  `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `friend_id` INT NOT NULL,
  `status` ENUM ('pending', 'accepted', 'declined', 'blocked') NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT (CURRENT_TIMESTAMP)
);

CREATE TABLE `token` (
  `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `token` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT (CURRENT_TIMESTAMP),
  `expired_at` TIMESTAMP NOT NULL
);

CREATE TABLE `messages` (
  `id` BIGINT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `room_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `content` TEXT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT (CURRENT_TIMESTAMP)
);

CREATE TABLE `post_comments` (
  `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `post_id` INT NOT NULL,
  `user_cmt_id` INT NOT NULL,
  `content` TEXT NOT NULL,
  `order` INT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT (CURRENT_TIMESTAMP),
  `updated_at` TIMESTAMP NOT NULL
);

CREATE TABLE `user_roles` (
  `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `role_id` INT NOT NULL
);

CREATE TABLE `post_comment_rep` (
  `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `cmt_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `content` TEXT NOT NULL,
  `order` INT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT (CURRENT_TIMESTAMP),
  `updated_at` TIMESTAMP NOT NULL
);

CREATE TABLE `users` (
  `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `status` INT NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT (CURRENT_TIMESTAMP)
);

CREATE TABLE `post_share` (
  `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `post_id` INT NOT NULL,
  `user_share_id` INT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT (CURRENT_TIMESTAMP)
);

CREATE TABLE `post_like` (
  `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `post_id` INT NOT NULL,
  `user_like_id` INT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT (CURRENT_TIMESTAMP)
);

ALTER TABLE `post_like` ADD CONSTRAINT `post_like_user_id_foreign` FOREIGN KEY (`user_like_id`) REFERENCES `users` (`id`);

ALTER TABLE `post_share` ADD CONSTRAINT `post_share_user_id_foreign` FOREIGN KEY (`user_share_id`) REFERENCES `users` (`id`);

ALTER TABLE `post_share` ADD CONSTRAINT `post_share_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`);

ALTER TABLE `posts` ADD CONSTRAINT `posts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `notifications` ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `friendships` ADD CONSTRAINT `friendships_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `token` ADD CONSTRAINT `token_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `media` ADD CONSTRAINT `media_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`);

ALTER TABLE `media` ADD CONSTRAINT `media_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `user_roles` ADD CONSTRAINT `user_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_name`);

ALTER TABLE `chat_rooms` ADD CONSTRAINT `chat_rooms_user_id_1_foreign` FOREIGN KEY (`user_id_1`) REFERENCES `users` (`id`);

ALTER TABLE `friendships` ADD CONSTRAINT `friendships_friend_id_foreign` FOREIGN KEY (`friend_id`) REFERENCES `users` (`id`);

ALTER TABLE `messages` ADD CONSTRAINT `messages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `chat_rooms` ADD CONSTRAINT `chat_rooms_user_id_2_foreign` FOREIGN KEY (`user_id_2`) REFERENCES `users` (`id`);

ALTER TABLE `user_info` ADD CONSTRAINT `user_info_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `post_comments` ADD CONSTRAINT `post_comments_user_cmt_id_foreign` FOREIGN KEY (`user_cmt_id`) REFERENCES `users` (`id`);

ALTER TABLE `user_roles` ADD CONSTRAINT `user_roles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `post_comment_rep` ADD CONSTRAINT `post_comment_rep_cmt_id_foreign` FOREIGN KEY (`cmt_id`) REFERENCES `post_comments` (`id`);

ALTER TABLE `post_comment_rep` ADD CONSTRAINT `post_comment_rep_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
