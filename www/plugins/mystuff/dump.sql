DROP TABLE IF EXISTS `prefix_topic_commented`;
CREATE TABLE `prefix_topic_commented` (
    topic_id    INT UNSIGNED NOT NULL,
    user_id     INT UNSIGNED NOT NULL,
    created     TIMESTAMP,
    
    PRIMARY KEY(topic_id, user_id),
    FOREIGN KEY(user_id) REFERENCES `prefix_user`(user_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY(topic_id) REFERENCES `prefix_topic`(topic_id) ON DELETE CASCADE ON UPDATE CASCADE
) Engine=InnoDB;

ALTER TABLE `prefix_topic` ADD `topic_last_update` DATETIME NOT NULL;
UPDATE `prefix_topic` SET `topic_last_update` = `topic_date_add`;