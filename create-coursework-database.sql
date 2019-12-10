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
-- Stored procedures for `message_metadata`
-- ----------------------------

DELIMITER $$
 
CREATE PROCEDURE GetMessageMetadata(
	IN metadata_id_to_get int(4)
)
BEGIN
	SELECT *
	FROM message_metadata
	WHERE metadata_id = metadata_id_to_get;
END$$
DELIMITER ;

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
-- Stored procedures for `message_content`
-- ----------------------------

DELIMITER $$
 
CREATE PROCEDURE GetMessages()
BEGIN
    SELECT 
    md.metadata_id,
		message_content_id,
		source_msisdn,
		destination_msisdn,
		received_time,
		switch_1,
		switch_2,
		switch_3,
		switch_4,
		fan,
		heater,
		keypad
    FROM
        message_metadata md
	join message_content c on md.metadata_id = c.metadata_id
    ORDER BY received_time;    
END$$
DELIMITER ;


DELIMITER $$
 
CREATE PROCEDURE AddMessage(
	IN source_msisdn_to_add varchar(15),
	IN destination_msisdn_to_add varchar(15),
	IN switch_1_to_add boolean,
	IN switch_2_to_add boolean,
	IN switch_3_to_add boolean,
	IN switch_4_to_add boolean,
	IN fan_to_add boolean,
	IN heater_to_add int(2),
	IN keypad_to_add char,
	IN received_time_to_add datetime
)
BEGIN
  SELECT @existing_time;

	SELECT received_time
	FROM message_content
	WHERE received_time = received_time_to_add
	INTO @existing_time;

	IF @existing_time IS NULL THEN

    SELECT @existing_metadata;

    SELECT DISTINCT metadata_id
    FROM message_metadata
    WHERE source_msisdn = source_msisdn_to_add
    and destination_msisdn = destination_msisdn_to_add
    INTO @existing_metadata;

    IF @existing_metadata IS null THEN

      INSERT INTO message_metadata (source_msisdn, destination_msisdn)
      VALUES (source_msisdn_to_add, destination_msisdn_to_add);
      SELECT LAST_INSERT_ID() INTO @existing_metadata;

    END IF	;

    INSERT INTO message_content
    (
      metadata_id,
      switch_1,
      switch_2,
      switch_3,
      switch_4,
      fan,
      heater,
      keypad,
      received_time
    )
    VALUES
    (
      @existing_metadata,
      switch_1_to_add,
      switch_2_to_add,
      switch_3_to_add,
      switch_4_to_add,
      fan_to_add,
      heater_to_add,
      keypad_to_add,
      received_time_to_add
    );
	END IF ;

END$$
DELIMITER ;

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
-- Stored procedures for `sessions`
-- ----------------------------

DELIMITER $$
 
CREATE PROCEDURE CheckSessionVar(
	IN local_session_id varchar(40),
	IN session_var_name varchar(40)
)
BEGIN
	SELECT session_var_name
	FROM sessions
	WHERE session_id = local_session_id
	AND session_var_name = session_var_name
	LIMIT 1;
END$$
DELIMITER ;


DELIMITER $$
 
CREATE PROCEDURE CreateSessionVar(
	IN local_session_id varchar(40),
	IN session_var_name varchar(40),
	IN session_var_value varchar(40)
)
BEGIN
	INSERT INTO sessions
	SET session_id = local_session_id,
	session_var_name = session_var_name,
	session_value = session_var_value;
END$$
DELIMITER ;


DELIMITER $$
 
CREATE PROCEDURE SetSessionVar(
	IN local_session_id varchar(40),
	IN session_var_name varchar(40),
	IN session_var_value varchar(40)
)
BEGIN
	UPDATE sessions
	SET session_value = session_var_value
	WHERE session_id = local_session_id
	AND session_var_name = session_var_name;
END$$
DELIMITER ;


DELIMITER $$
 
CREATE PROCEDURE GetSessionVar(
	IN local_session_id varchar(40),
	IN session_var_name varchar(40)
)
BEGIN
	SELECT session_value
	FROM sessions
	WHERE session_id = local_session_id
	AND session_var_name = session_var_name;
END$$
DELIMITER ;

-- ----------------------------
-- Table structure for `users`
-- ----------------------------

CREATE TABLE `users` (
	`user_id` int(4) NOT NULL AUTO_INCREMENT,
	`username` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
	`hashed_password` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
	`privilege` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
	PRIMARY KEY (user_id)
) AUTO_INCREMENT=100 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Stored procedures for `users`
-- ----------------------------

DELIMITER $$
 
CREATE PROCEDURE AddUser(
	IN name varchar(30),
	IN hashed_pw varchar(500),
	IN privs varchar(10)
)
BEGIN
	INSERT INTO `users` (username, hashed_password, privilege) 
	VALUES (name, hashed_pw, privs);
END$$
DELIMITER ;


DELIMITER $$
 
CREATE PROCEDURE DeleteUser(
	IN user_id_to_delete int(4)
)
BEGIN
	DELETE FROM users
	WHERE user_id = user_id_to_delete;
END$$
DELIMITER ;


DELIMITER $$
 
CREATE PROCEDURE TogglePrivilege(
	IN user_id_to_toggle int(4)
)
BEGIN
	UPDATE users
	SET privilege = !privilege
	WHERE user_id = user_id_to_toggle;
END$$
DELIMITER ;

DELIMITER $$
 
CREATE PROCEDURE UpdateUser(
	IN user_id_to_update int(4),
	IN name varchar(30),
	IN hashed_pw varchar(500),
	IN privs varchar(10)
)
BEGIN
	UPDATE users 
	SET username = name,
	hashed_password = hashed_pw,
	privilege = privs 
	WHERE user_id = user_id_to_update;
END$$
DELIMITER ;


-- ----------------------------
-- Table structure for `error_log`
-- ----------------------------

CREATE TABLE `error_log` (
	`error_id` int(4) NOT NULL AUTO_INCREMENT,
	`error_message` varchar(800) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
	`error_datetime` datetime DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (error_id)
) AUTO_INCREMENT=100 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Stored procedures for `error_log`
-- ----------------------------

DELIMITER $$
 
CREATE PROCEDURE LogError(
	IN error_message_content varchar(800)
)
BEGIN
	INSERT INTO `error_log` (error_message) 
	VALUES (error_message_content);
END$$
DELIMITER ;

