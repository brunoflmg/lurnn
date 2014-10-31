 CREATE TABLE `discussions` (
`post_id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`class_id` BIGINT NOT NULL ,
`posted_by` VARCHAR( 255 ) NOT NULL ,
`post_title` VARCHAR( 255 ) NOT NULL ,
`post_content` LONGTEXT NOT NULL ,
`skills` VARCHAR( 255 ) NOT NULL ,
`has_attachment` BOOL NOT NULL DEFAULT '0',
`created_date` DATETIME NOT NULL ,
`last_updated` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci 

 CREATE TABLE `discussions_comments` (
`comment_id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`post_id` BIGINT NOT NULL ,
`comments` TEXT NOT NULL ,
`commented_by` VARCHAR( 255 ) NOT NULL ,
`created_date` DATETIME NOT NULL ,
`last_updated` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci 

CREATE TABLE `discussions_attachment` (
`attachment_id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`post_id` BIGINT NOT NULL ,
`file_path` VARCHAR( 255 ) NOT NULL ,
`file_type` ENUM( 'video', 'picture', 'other' ) NOT NULL DEFAULT 'picture',
`created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci