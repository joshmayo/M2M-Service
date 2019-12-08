<?php
/**
 * Created by PhpStorm.
 * User: P16190097
 * Date: 10/11/19
 * Time: 10:05
 *
 *  class for retreiving messages from the M2M service via SOAP
 * todo: add error handling to soap call
 */

namespace M2MConnect;

class MessageDetailsModel
{
    private $result;
    private $xml_parser;
    private $soap_wrapper;
    private $message_database;
    private $database_connection_settings;

    public function __construct()
    {
        $this->soap_wrapper = null;
        $this->xml_parser = null;
        $this->result = '';
        $this->message_database = null;
        $this->database_connection_settings = null;
    }

    public function __destruct()
    {
    }

    public function setSoapWrapper($soap_wrapper)
    {
        $this->soap_wrapper = $soap_wrapper;
    }

    public function setDatabaseWrapper($db_wrapper)
    {
        $this->message_database = $db_wrapper;
    }

    public function setDatabaseConnectionSettings($database_connection_settings)
    {
        $this->database_connection_settings = $database_connection_settings;
    }

    public function getResult()
    {
        return $this->result;
    }

    public function retrieveMessages()
    {
        $soap_client_handle = $this->soap_wrapper->createSoapClient();

        if ($soap_client_handle !== false) {
            $webservice_function = 'peekMessages';
            $webservice_call_parameters = [
                'username' => M2M_USER,
                'password' => M2M_PASS,
                'count' => 25,
                'deviceMsisdn' => '',
                'countryCode' => '44'
            ];
            $webservice_value = 'peekMessagesResponse';
            $soapcall_result = $this->soap_wrapper->performSoapCall($soap_client_handle, $webservice_function,
                $webservice_call_parameters, $webservice_value);

            $this->result = $soapcall_result;
        }
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
    
    public function sendMessage($message_body)
    {
        $soap_client_handle = $this->soap_wrapper->createSoapClient();
        if($soap_client_handle !== false) {

            $webservice_function = 'sendMessage';
            $webservice_call_parameters = [
                'username' => M2M_USER,
                'password' => M2M_PASS,
                'deviceMsisdn' => COUNTRY_CODE . MSISDN,
                'message' => $message_body,
                'deliveryReport' => true,
                'mtBearer' => 'SMS',
            ];
            $webservice_value = 'peekMessagesResponse';
            $soapcall_result = $this->soap_wrapper->performSoapCall($soap_client_handle, $webservice_function,
                $webservice_call_parameters, $webservice_value);

            $this->result = $soapcall_result;
        }
    }
}