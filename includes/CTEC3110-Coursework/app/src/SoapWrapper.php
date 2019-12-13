<?php
/**
 * Creates and initialises soap client for calling M2M service
 *
 * @author Joshua Mayo, Sophie Hughes, Kieran McCrory
 *
 * todo: possibly remove soapCall magic function for appropriate alternative
 */

namespace M2MConnect;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class SoapWrapper
{
    private $log;

    public function __construct()
    {
        $this->log = new Logger('logger');
        $this->log->pushHandler(new StreamHandler(LOGS_PATH . 'soap.log',Logger::INFO));
        $this->log->pushHandler(new StreamHandler(LOGS_PATH . 'soap_error.log',Logger::ERROR));
    }

    public function __destruct()
    {
    }

    /**
     *
     * @return \SoapClient|string
     *
     * @uses WSDl endpoint specified in settings.php for request.
     *
     * Intialises and handles a new soap client instance.
     *
     *
     */
    public function createSoapClient()
    {
        $wsdl = WSDL;

        $soap_client_parameters = ['trace' => true, 'exceptions' => true];

        try {
            $this->log->info('Attempting to create soap client.');
            $soap_client_handle = new \SoapClient($wsdl, $soap_client_parameters);
        } catch (\SoapFault $exception) {
            $soap_client_handle = 'Ooops - something went wrong when connecting to the data supplier.  Please try again later';
            $this->log->error('An error was encountered when attempting to create soap client.');
        }
        return $soap_client_handle;
    }

    /**
     * @param $soap_client
     * @param $webservice_function
     * @param $webservice_call_parameters
     * @param $webservice_value
     *
     *
     * Soap call is performed with stucture provided by MessageDetailsModel
     *
     * @return null|string by default empty for $soap_call_result
     */

    public function performSoapCall($soap_client, $webservice_function, $webservice_call_parameters, $webservice_value)
    {
        $soap_call_result = null;

        if ($soap_client) {
            try {
                $this->log->info('Attempting to perform soap call: ' . $webservice_function);

                $webservice_call_result = $soap_client->__soapCall($webservice_function, $webservice_call_parameters);
                $soap_call_result = $webservice_call_result;
            } catch (Exception $exception) {
                // \SoapFault
                $soap_call_result = $exception->getMessage();
                $this->log->error('An error was encountered when attempting to perform soap call: ' . $webservice_function);
            }
        }
        return $soap_call_result;
    }
}