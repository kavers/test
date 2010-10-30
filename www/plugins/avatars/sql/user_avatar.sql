CREATE TABLE `prefix_user_avatar` (
    user_id         INT UNSIGNED NOT NULL,
    avatar_title    VARCHAR(50) NOT NULL,
    
    PRIMARY KEY(user_id, avatar_title),
    FOREIGN KEY(user_id) REFERENCES `prefix_user`(user_id) ON DELETE CASCADE ON UPDATE CASCADE,
) Engine=InnoDB;