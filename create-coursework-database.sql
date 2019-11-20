SET FOREIGN_KEY_CHECKS=0;

DROP DATABASE IF EXISTS coursework_db;

CREATE DATABASE IF NOT EXISTS coursework_db COLLATE utf8_unicode_ci;

--
-- Create the user account
--
GRANT SELECT, INSERT ON coursework_db.* TO coursework_user@localhost IDENTIFIED BY 'coursework_user_pass';

USE coursework_db;

-- ----------------------------
-- Table structure for `error_log`
-- ----------------------------

DROP TABLE IF EXISTS `error_log`;
CREATE TABLE `error_log` (
  `error_id` int(4) NOT NULL AUTO_INCREMENT,
  `error` varchar(800) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`error_id`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `message_metadata`
-- ----------------------------

DROP TABLE IF EXISTS `message_metadata`;
CREATE TABLE `message_metadata` (
  `metadata_id` int(4) NOT NULL AUTO_INCREMENT,
  `source_msisdn` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `destination_msisdn` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `recieved_time` datetime CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`metadata_id`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `message_content`
-- ----------------------------

DROP TABLE IF EXISTS `message_content`;
CREATE TABLE `message_content` (
  `message_content_id` int(4) NOT NULL AUTO_INCREMENT,
  `metadata_id` int(4) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL REFERENCES message_metadata(metadata_id`),
  `switch_1` boolean CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `switch_2` boolean CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `switch_3` boolean CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `switch_4` boolean CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `fan` boolean CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `heater` int(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `keypad` char CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`message_content_id`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8;