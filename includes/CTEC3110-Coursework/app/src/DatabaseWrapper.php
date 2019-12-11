<?php

/**
 * DatabaseWrapper.php
 *
 * Wrapper class for accessing the database.
 *
 * Date: 02/12/2019
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
    
    public function setDatabaseConnectionSettings($database_connection_settings)
    {
        $this->database_connection_settings = $database_connection_settings;
    }

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
            $error_message .= 'Error: ' . var_dump($this->prepared_statement->errorInfo(), true) . "\n";

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

    public function getMessageMetaData($metadata_id)
    {
        $query_string = 'CALL GetMessageMetadata()';

        $query_parameters = [
            ':metadata_id_to_get' => $metadata_id
        ];

        $this->safeQuery($query_string, $query_parameters);

        if ($this->countRows() > 0) {
            $metadata = $this->safeFetchRow();
        }
        return $metadata;
    }

    public function getMessages()
    {
        $this->makeDatabaseConnection();
        $messages = [];
        $query_string = 'CALL GetMessages()';

        $this->safeQuery($query_string);

        if ($this->countRows() > 0) {
            $messages = $this->safeFetchArray();
        }

        return $messages;
    }

    public function addMessage(Message $message)
    {
        $this->makeDatabaseConnection();
        $date = DateTime::createFromFormat('d/m/Y H:i:s', $message->getReceivedTime());
        $dateToBeInserted = $date->format('Y-m-d H:i:s');

        $query_string = 'CALL AddMessage(' . $message->getSourceMsisdn() . ','
            . $message->getDestinationMsisn() . ','
            . $message->getSwitch1() . ','
            . $message->getSwitch2() . ','
            . $message->getSwitch3() . ','
            . $message->getSwitch4() . ','
            . $message->getFan() . ','
            . $message->getHeater() . ','
            . $message->getKeypad() . ',\''
            . $dateToBeInserted . '\')';

        $this->safeQuery($query_string);
    }

    public function addUser($name, $hashed_pw, $privs)
    {
        $query_string = 'CALL AddUser()';

        $query_parameters = [
            ':name' => $name,
            ':hashed_pw' => $hashed_pw,
            ':privs' => $privs
        ];

        $this->safeQuery($query_string, $query_parameters);
    }

    public function deleteUser($user_id)
    {
        $query_string = 'CALL DeleteUser()';

        $query_parameters = [
            ':user_id_to_delete' => $user_id
        ];

        $this->safeQuery($query_string, $query_parameters);
    }

    public function togglePrivilege($user_id)
    {
        $query_string = 'CALL TogglePrivilege()';

        $query_parameters = [
            ':user_id_to_toggle' => $user_id
        ];

        $this->safeQuery($query_string, $query_parameters);
    }

    public function updateUser($user_id, $name, $hashed_pw, $privs)
    {
        $query_string = 'CALL UpdateUser()';

        $query_parameters = [
            ':user_id_to_update' => $user_id,
            ':name' => $name,
            ':hashed_pw' => $hashed_pw,
            ':privs' => $privs
        ];

        $this->safeQuery($query_string, $query_parameters);
    }

    public function unsetSessionVar($session_key)
    {
    }

    public function setSessionVar($session_key, $session_value)
    {
        if ($this->getSessionVar($session_key) === true) {
            $this->storeSessionVar($session_key, $session_value);
        } else {
            $this->createSessionVar($session_key, $session_value);
        }

        return ($this->errors);
    }

    public function getSessionVar($session_key)
    {
        $session_var_exists = false;
        $query_string = 'CALL CheckSessionVar()';

        $query_parameters = [
            ':local_session_id' => session_id(),
            ':session_var_name' => $session_key
        ];

        $this->safeQuery($query_string, $query_parameters);

        if ($this->countRows() > 0) {
            $session_var_exists = true;
        }
        return $session_var_exists;
    }

    private function createSessionVar($session_key, $session_value)
    {
        $query_string = 'CALL CreateSessionVar()';

        $query_parameters = [
            ':local_session_id' => session_id(),
            ':session_var_name' => $session_key,
            ':session_var_value' => $session_value
        ];

        $this->safeQuery($query_string, $query_parameters);
    }

    private function storeSessionVar($session_key, $session_value)
    {
        $query_string = 'CALL SetSessionVar';

        $query_parameters = [
            ':local_session_id' => session_id(),
            ':session_var_name' => $session_key,
            ':session_var_value' => $session_value
        ];

        $this->safeQuery($query_string, $query_parameters);
    }
}
