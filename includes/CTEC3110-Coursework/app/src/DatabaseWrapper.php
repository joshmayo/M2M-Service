<?php

/**
 * DatabaseWrapper.php
 *
 * Wrapper class for accessing the database and performing all db activities.
 *
 *
 * @author Joshua Mayo, Sophie Hughes, Kieran McCrory
 *
 */

namespace M2MConnect;

use PDO;
use DateTime;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class DatabaseWrapper
{
    private $database_connection_settings;
    private $db_handle;
    private $sql_queries;
    private $prepared_statement;
    private $errors;
    private $log;

    public function __construct()
    {
        $this->database_connection_settings = null;
        $this->db_handle = null;
        $this->sql_queries = null;
        $this->prepared_statement = null;
        $this->errors = [];

        $this->log = new Logger('logger');
        $this->log->pushHandler(new StreamHandler(LOGS_PATH . 'database.log', Logger::INFO));
        $this->log->pushHandler(new StreamHandler(LOGS_PATH . 'database_error.log', Logger::ERROR));
    }

    public function __destruct()
    {
    }

    public function getVars()
    {
        $vars = [
            $this->database_connection_settings,
            $this->db_handle,
            $this->sql_queries,
            $this->prepared_statement,
            $this->errors,
            $this->log
        ];

        return $vars;
    }

    public function setDatabaseConnectionSettings($database_connection_settings)
    {
        $this->database_connection_settings = $database_connection_settings;
    }


    /**
     * Function that creates and executes a PDO object to connect to the database.
     *
     * @return string - Only returned when a PDO call encounters an error, will contain multiple errors.
     *
     * Connection settings are stored as hard variables
     */
    public function makeDatabaseConnection()
    {
        $pdo_error = '';

        $database_settings = $this->database_connection_settings;
        $host_name = $database_settings['rdbms'] . ':host=' . $database_settings['host'];
        $port_number = ';port=' . '3306';
        $user_database = ';dbname=' . $database_settings['db_name'];
        $host_details = $host_name . $port_number . $user_database;
        $user_name = $database_settings['user_name'];
        $user_password = $database_settings['user_password'];
        $pdo_attributes = $database_settings['options'];

        try {
            $this->log->info('Attempting to connect to database.');
            $pdo_handle = new \PDO($host_details, $user_name, $user_password, $pdo_attributes);
            $this->db_handle = $pdo_handle;
        } catch (\PDOException $exception_object) {
            trigger_error('error connecting to database');
            $this->log->error('Error occurred when attempting to connect to database.');
            $pdo_error = 'error connecting to database';
        }

        return $pdo_error;
    }

    /**
     * @param $query_string
     * @param null $params
     *
     * For transparency, each parameter value is bound separately to its placeholder
     * This is not always strictly necessary.
     *
     * @return mixed
     */
    private function safeQuery($query_string, $params = null)
    {
        $this->errors['db_error'] = false;
        $query_parameters = $params;

        try {
            $this->log->info('Attempting to execute query: ' . $query_string . $query_parameters);
            $this->prepared_statement = $this->db_handle->prepare($query_string);
            $execute_result = $this->prepared_statement->execute($query_parameters);
            $this->errors['execute-OK'] = $execute_result;
        } catch (PDOException $exception_object) {
            $error_message = 'PDO Exception caught. ';
            $error_message .= 'Error with the database access.' . "\n";
            $error_message .= 'SQL query: ' . $query_string . "\n";
            $error_message .= 'Error: ' . var_dump($this->prepared_statement->errorInfo(),
                    true) . "\n";

            $this->log->error('Error occurred when attempting to execute query: ' . $query_string . $query_parameters);

            $this->errors['db_error'] = true;
            $this->errors['sql_error'] = $error_message;
        }
        return $this->errors['db_error'];
    }

    public function countRows()
    {
        $num_rows = $this->prepared_statement->rowCount();
        return $num_rows;
    }

    public function safeFetchRow()
    {
        $record_set = $this->prepared_statement->fetch(PDO::FETCH_NUM);
        return $record_set;
    }

    public function safeFetchArray()
    {
        $row = $this->prepared_statement->fetchAll();
        $this->prepared_statement->closeCursor();
        return $row;
    }

    /**
     * Find message Meta Data associated with the Message ID
     *
     * @param $metadata_id
     *
     * @return mixed
     */
    public function getMessageMetaData($metadata_id)
    {
        $query_string = 'SELECT *
	                    FROM message_metadata
	                    WHERE metadata_id =' . $metadata_id;

        $this->safeQuery($query_string);

        if ($this->countRows() > 0) {
            $metadata = $this->safeFetchRow();
        }
        return $metadata;
    }

    /**
     * Retrieves all stored messages on the database
     *
     * @return array|mixed - All stored messages will be returned via this array.
     */
    public function getMessages()
    {
        $this->makeDatabaseConnection();
        $messages = [];
        $query_string = 'SELECT 
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
        ORDER BY received_time DESC';

        $this->safeQuery($query_string);

        if ($this->countRows() > 0) {
            $messages = $this->safeFetchArray();
        }

        return $messages;
    }

    /**
     * Adds a new message to the database.
     *
     * @param Message $message - Message object passed to the function from the Message.php class.
     *
     * @return array|mixed - Whether or not the message is new.
     */

    public function addMessage(Message $message)
    {
        $new_message = [];
        $existing_time = [];
        $metadata_id = [];
        $this->makeDatabaseConnection();
        $date = DateTime::createFromFormat('d/m/Y H:i:s', $message->getReceivedTime());
        $dateToBeInserted = $date->format('Y-m-d H:i:s');

        $query_string = 'SELECT received_time
	    FROM message_content
    	WHERE received_time = \'' . $dateToBeInserted . '\'';

        $this->safeQuery($query_string);

        if ($this->countRows() > 0) {
            $existing_time = $this->safeFetchArray();
        }

        $this->log->info('Existing_time (' . implode(" ", $existing_time[0]) .')');

        if(is_null(implode(" ", $existing_time[0])) || empty(implode(" ", $existing_time[0])))
        {
            $query_string = 'SELECT DISTINCT metadata_id
            FROM message_metadata
            WHERE source_msisdn = ' . $message->getSourceMsisdn() . '
                and destination_msisdn = ' . $message->getDestinationMsisn();

            $this->safeQuery($query_string);

            if ($this->countRows() > 0) {
                $metadata_id = $this->safeFetchArray();
            }

            $this->log->info('Metadata_id (' . implode(" ", $metadata_id[0]) .')');

            if(is_null(implode(" ", $metadata_id[0])) || empty(implode(" ", $metadata_id[0])))
            {
                $query_string = 'INSERT INTO message_metadata (source_msisdn, destination_msisdn)
                VALUES (' . $message->getSourceMsisdn() . ', ' . $message->getDestinationMsisn();

                $this->safeQuery($query_string);
            }

            $query_string = 'INSERT INTO message_content
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
            (' . implode(" ", $metadata_id[0]) . ',
            ' . $message->getSwitch1() . ',
                ' . $message->getSwitch2() . ',
                ' . $message->getSwitch3() . ',
                ' . $message->getSwitch4() . ',
                ' . $message->getFan() . ',
                ' . $message->getHeater() . ',
                ' . $message->getKeypad() . ',
                \'' . $dateToBeInserted . '\'
                )';

            $this->safeQuery($query_string);

            $query_string = 'SELECT LAST_INSERT_ID()';

            $this->safeQuery($query_string);

            if ($this->countRows() > 0) {
                $new_message = $this->safeFetchArray();
            }
        }

        return $new_message;
    }

    /**
     * Adds a Database User
     *
     * priv '0' = admin
     * priv '1' = user
     * priv '2' = superAdmin
     *
     * @param $name
     * @param $hashed_pw
     * @param $privs
     *
     * @return array|mixed - Whether or not the insertion was successful
     */

    public function addUser($name, $hashed_pw, $privs)
    {
        $this->makeDatabaseConnection();
        $query_string = 'INSERT INTO users (username, hashed_password, privilege)
	    VALUES (\'' . $name . '\', \'' . $hashed_pw . '\', \'' . $privs . '\')';

        $result = $this->safeQuery($query_string);

        return $result;
    }

    /**
     * Removes a Database User
     *
     * @param $user_id
     */

    public function deleteUser($user_id)
    {
        $this->makeDatabaseConnection();
        $query_string = 'DELETE FROM users
	    WHERE user_id =' . $user_id;

        $this->safeQuery($query_string);
    }

    /**
     * @param $user_id
     */

    public function togglePrivilege($user_id)
    {
        $this->makeDatabaseConnection();
        $query_string = 'UPDATE users SET privilege = IF(privilege = 1, 0, 1)
        WHERE user_id = ' . $user_id;

        $this->safeQuery($query_string);
    }

    /**
     * Changes Database User data.
     *
     * @param $user_id
     * @param $name
     * @param $hashed_pw
     * @param $privs
     */

    public function updateUser($user_id, $name, $hashed_pw, $privs)
    {
        $this->makeDatabaseConnection();
        $query_string = 'UPDATE users 
	    SET username = ' . $name . ',
	    hashed_password = ' . $hashed_pw . ',
	    privilege = ' . $privs . ' 
	    WHERE user_id = ' . $user_id;

        $this->safeQuery($query_string);
    }

    /**
     * Gets the password hash for a given user
     *
     * @param $username
     * @return hash
     */

    public function getHash($username)
    {
        $this->makeDatabaseConnection();
        $query_string = 'SELECT hashed_password
        FROM `users`
        WHERE username = \'' . $username . '\'';

        $this->safeQuery($query_string);

        if ($this->countRows() > 0) {
            $hash = $this->safeFetchArray();
        }

        return $hash[0]['hashed_password'];
    }


    /**
     * Gets the user details of a given user
     *
     * @param $username
     * @return array
     */

    public function getUser($username)
    {
        $user = [];
        $this->makeDatabaseConnection();
        $query_string = 'SELECT username, hashed_password, privilege  
	    FROM `users`
        WHERE username = \'' . $username . '\'';

        $this->safeQuery($query_string);

        if ($this->countRows() > 0) {
            $user = $this->safeFetchArray();
            return $user['0'];
        }

        return $user;
    }

    /**
     * Gets the user details of all users
     *
     * @return array
     */

    public function getAllUsers()
    {
        $this->makeDatabaseConnection();
        $query_string = 'SELECT username, privilege, user_id
        FROM `users`
        WHERE privilege != 2';

        $this->safeQuery($query_string);

        if ($this->countRows() > 0) {
            $users = $this->safeFetchArray();
        }

        return $users;
    }
}
