<?php
/**
 * MessageDbModel.php
 *
 * Class for
 */

namespace M2MConnect;


class MessageDbModel
{
    private $message_database;
    private $database_connection_settings;

    public function __construct()
    {
        $this->message_database = null;
        $this->database_connection_settings = null;
    }

    public function __destruct()
    {
    }

    public function setDatabaseWrapper($db_wrapper)
    {
        $this->message_database = $db_wrapper;
    }

    public function setDatabaseConnectionSettings($database_connection_settings)
    {
        $this->database_connection_settings = $database_connection_settings;
    }

    public function addMessage($message, $database, $settings)
    {
        $database->setDatabaseConnectionSettings($settings);
        $database->makeDatabaseConnection();

        $database->addMessage($message);
    }

    public function getMessagesFromDatabase($database, $settings)
    {
        $database->setDatabaseConnectionSettings($settings);
        $database->makeDatabaseConnection();

        $messages = $database->getMessages();

        return $messages;
    }

}