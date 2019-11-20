<?php
/**
 * Created by PhpStorm.
 * User: slim
 * Date: 24/10/17
 * Time: 10:01
 */

namespace M2MConnect;

class MessageDetailsModel
{
    private $country_code;
    private $detail;
    private $result;
    private $xml_parser;
    private $soap_wrapper;

    public function __construct()
    {
        $this->soap_wrapper = null;
        $this->xml_parser = null;
        $this->country_code = '';
        $this->detail = '';
        $this->result = [];
    }

    public function __destruct(){}

    public function setSoapWrapper($soap_wrapper)
    {
        $this->soap_wrapper = $soap_wrapper;
    }

    public function getResult()
    {
        return $this->result;
    }

    public function retrieveMessages()
    {
        $soap_client_handle = $this->soap_wrapper->createSoapClient();

        if ($soap_client_handle !== false)
        {
            $webservice_function = 'peekMessages';
            $webservice_call_parameters = [
                'username' => M2M_USER,
                'password' => M2M_PASS,
                'count' => 25,
                'deviceMsisdn' => '',
                'countryCode' => '44'
            ];
            $webservice_value = 'peekMessagesResponse';
            $soapcall_result = $this->soap_wrapper->performSoapCall($soap_client_handle, $webservice_function, $webservice_call_parameters, $webservice_value);

            $this->result = $soapcall_result;
        }
    }
}