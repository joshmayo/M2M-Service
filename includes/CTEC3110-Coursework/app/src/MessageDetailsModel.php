<?php
/**
 * MessageDetailsModel.php
 *
 * Class for sending and retrieving messages from the M2M service via SOAP calls.
 *
 * @author Joshua Mayo, Sophie Hughes, Kieran McCrory
 *
 * todo: add error handling to soap call
 */

namespace M2MConnect;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class MessageDetailsModel
{
    private $result;
    private $xml_parser;
    private $soap_wrapper;
    private $log;

    public function __construct()
    {
        $this->soap_wrapper = null;
        $this->xml_parser = null;
        $this->result = '';

        $this->log = new Logger('logger');
        $this->log->pushHandler(new StreamHandler(LOGS_PATH . 'soap.log', Logger::INFO));
        $this->log->pushHandler(new StreamHandler(LOGS_PATH . 'soap_error.log', Logger::ERROR));
    }

    public function __destruct()
    {
    }

    public function setSoapWrapper($soap_wrapper)
    {
        $this->soap_wrapper = $soap_wrapper;
    }

    public function getResult()
    {
        return $this->result;
    }

    /**
     * @return null
     */
    public function getSoapWrapper()
    {
        return $this->soap_wrapper;
    }

    /**
     * @return Logger
     */
    public function getLog(): Logger
    {
        return $this->log;
    }

    public function retrieveMessages()
    {
        $this->log->info('Attempting to retrieve messages from API.');

        $soap_client_handle = $this->soap_wrapper->createSoapClient();

        if ($soap_client_handle !== false) {
            $webservice_function = 'peekMessages';
            $webservice_call_parameters = [
                'username' => M2M_USER,
                'password' => M2M_PASS,
                'count' => 1000,
                'deviceMsisdn' => '',
                'countryCode' => '44'
            ];

            $webservice_value = 'peekMessagesResponse';
            $soapcall_result = $this->soap_wrapper->performSoapCall($soap_client_handle,
                $webservice_function,
                $webservice_call_parameters, $webservice_value);

            $this->result = $soapcall_result;
        }
    }

    /**
     * Calls the M2M API to send the supplied message body.
     *
     *
     * @param $message_body
     * @param $device_msisdn
     */
    public function sendMessage($message_body, $device_msisdn = COUNTRY_CODE . MSISDN)
    {
        $this->log->info('Attempting to send message to API: ' . $message_body);

        $soap_client_handle = $this->soap_wrapper->createSoapClient();

        if ($soap_client_handle !== false) {

            $webservice_function = 'sendMessage';
            $webservice_call_parameters = [
                'username' => M2M_USER,
                'password' => M2M_PASS,
                'deviceMsisdn' => $device_msisdn,
                'message' => $message_body,
                'deliveryReport' => true,
                'mtBearer' => 'SMS',
            ];
            $webservice_value = 'sendMessageResponse';

            $soapcall_result = $this->soap_wrapper->performSoapCall($soap_client_handle,
                $webservice_function,
                $webservice_call_parameters, $webservice_value);

            $this->result = $soapcall_result;
        }
    }
}