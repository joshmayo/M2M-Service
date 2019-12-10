<?php
/**
 *  creates and initialises soap client for calling M2M service
 *
 * todo: possibly remove soapCall magic function for appropriate alternative
 */

namespace M2MConnect;

class SoapWrapper
{

    public function __construct()
    {
    }

    public function __destruct()
    {
    }

    public function createSoapClient()
    {
        $wsdl = WSDL;

        $soap_client_parameters = ['trace' => true, 'exceptions' => true];

        try {
            $soap_client_handle = new \SoapClient($wsdl, $soap_client_parameters);
            //var_dump($soap_client_handle->__getFunctions());
//            var_dump($soap_client_handle->__getTypes());
        } catch (\SoapFault $exception) {
            $soap_client_handle = 'Ooops - something went wrong when connecting to the data supplier.  Please try again later';
        }
        return $soap_client_handle;
    }

    public function performSoapCall($soap_client, $webservice_function, $webservice_call_parameters, $webservice_value)
    {
        $soap_call_result = null;
        $raw_xml = '';

        if ($soap_client) {
            try {
                //old soapCall code. May need to swap magic function for it
                //$webservice_call_result = $soap_client->{$webservice_function}($webservice_call_parameters);
                // $soap_call_result = $webservice_call_result->{$webservice_value};

                $webservice_call_result = $soap_client->__soapCall($webservice_function, $webservice_call_parameters);
                $soap_call_result = $webservice_call_result;
            } catch (Exception $exception) {
                //\SoapFault
                $soap_call_result = $exception->getMessage();
            }
        }
        return $soap_call_result;
    }
}