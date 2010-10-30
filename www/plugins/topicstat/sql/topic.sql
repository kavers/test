ALTER TABLE `prefix_topic` ADD (
 `topic_count_unique_read` INT UNSIGNED NOT NULL DEFAULT 0,
 `topic_count_users_read` INT UNSIGNED NOT NULL DEFAULT 0
);