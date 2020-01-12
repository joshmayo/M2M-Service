<?php
/**
 * SoapWrapperTest.php
 *
 * Unit Tests for SoapWrapper class
 *
 * @uses \M2MConnect\SoapWrapper
 *
 * @author Joshua Mayo, Sophie Hughes, Kieran McCrory
 */

namespace M2MConnect;

use PHPUnit\Framework\TestCase;

class SoapWrapperTest extends TestCase
{
    protected function setUp(): void
    {
        $this->config = require_once("../app/settings.php");
    }

    public function test__construct()
    {
        $testSoapWrapper = new SoapWrapper();
        $this->assertIsObject($testSoapWrapper);
        $this->assertNotEmpty($testSoapWrapper->getLog());


    }

    public function testCreateSoapClient()
    {
        $testSoapWrapper = new SoapWrapper();

        var_dump($testSoapWrapper->createSoapClient());
        $this->assertIsObject($testSoapWrapper->createSoapClient());
        $this->assertNotEmpty($testSoapWrapper->createSoapClient());

    }

    public function testPerformSoapCall()
    {
        $soapClientMock = $this->getMockFromWsdl(WSDL);
        $soapClientMock->method('peekMessages');

        $webservice_value = "";

        $webservice_call_parameters = [
            'username' => M2M_USER,
            'password' => M2M_PASS,
            'count' => 1000,
            'deviceMsisdn' => '',
            'countryCode' => '44'
        ];

        $testSoapWrapper = new SoapWrapper();

        $this->assertNull($testSoapWrapper->performSoapCall($soapClientMock, 'peekMessages',
            $webservice_call_parameters,
            $webservice_value));
    }


}
