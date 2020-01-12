SET FOREIGN_KEY_CHECKS=0;

DROP DATABASE IF EXISTS coursework_db;

CREATE DATABASE IF NOT EXISTS coursework_db COLLATE utf8_unicode_ci;

--
-- Create the user account
--
GRANT EXECUTE ON coursework_db.* TO coursework_user@localhost IDENTIFIED BY 'coursework_user_pass';

USE coursework_db;

-- ----------------------------
-- Table structure for `message_metadata`
-- ----------------------------

DROP TABLE IF EXISTS `message_metadata`;
CREATE TABLE `message_metadata` (
	`metadata_id` int(4) NOT NULL AUTO_INCREMENT,
	`source_msisdn` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
	`destination_msisdn` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
	PRIMARY KEY (metadata_id)
) AUTO_INCREMENT=100 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `message_content`
-- ----------------------------

DROP TABLE IF EXISTS `message_content`;
CREATE TABLE `message_content` (
	`message_content_id` int(4) NOT NULL AUTO_INCREMENT,
	`metadata_id` int(4),
	`switch_1` boolean,
	`switch_2` boolean,
	`switch_3` boolean,
	`switch_4` boolean,
	`fan` boolean,
	`heater` int(2),
	`keypad` char CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
	`received_time` datetime DEFAULT NULL,
	PRIMARY KEY (message_content_id),
	FOREIGN KEY (metadata_id) REFERENCES message_metadata(metadata_id)
) AUTO_INCREMENT=100 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `sessions`
-- ----------------------------

CREATE TABLE `sessions` (
	`session_id` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
	`session_var_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
	`session_value` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
	`session_timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
	PRIMARY KEY (session_id)
) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='CURRENT_TIMESTAMP';

-- ----------------------------
-- Table structure for `users`
-- ----------------------------

CREATE TABLE `users` (
	`user_id` int(4) NOT NULL AUTO_INCREMENT,
	`username` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci UNIQUE,
	`hashed_password` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
	`privilege` int(1) DEFAULT NULL,
	PRIMARY KEY (user_id)
) AUTO_INCREMENT=100 DEFAULT CHARSET=utf8;

INSERT INTO `users`(username, hashed_password, privilege) VALUES ('admin', '$2y$12$u/UEuYxcHNsYuV5y9rUoBuxhscADck0y45YFFQI1erHsi1325W5z.', 0);
INSERT INTO `users`(username, hashed_password, privilege) VALUES ('demo2',
'$2y$12$u/UEuYxcHNsYuV5y9rUoBuxhscADck0y45YFFQI1erHsi1325W5z.', 1);

INSERT INTO `users`(username, hashed_password, privilege) VALUES ('demo1',
'$2y$12$u/UEuYxcHNsYuV5y9rUoBuxhscADck0y45YFFQI1erHsi1325W5z.', 1);

INSERT INTO `users`(username, hashed_password, privilege) VALUES ('superAdmin', '$2y$12$u/UEuYxcHNsYuV5y9rUoBuxhscADck0y45YFFQI1erHsi1325W5z.', 2);


DROP TABLE IF EXISTS `error_log`;