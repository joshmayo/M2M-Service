<?php
/**
 * ProcessMessageTest.php
 *
 * Unit Tests for ProcessMessage class
 *
 * @uses \M2MConnect\ProcessMessage
 *
 * @author Joshua Mayo, Sophie Hughes, Kieran McCrory
 */


namespace M2MConnect;

use PHPUnit\Framework\TestCase;
use Slim\App;
use Slim\Container;

class ProcessMessageTest extends TestCase
{
   protected $test_db_config;
   protected $config;
   protected $testApp;


    protected function setUp(): void
    {
        $testSoapContainer = new Container();

        $this->config = require_once("../app/settings.php");
        $this->test_db_config = (TEST_DB_SETTINGS['pdo_test_settings']);

        $testApp = new App($testSoapContainer);

        $testSoapContainer = $testApp->getContainer();

        $testSoapContainer['soapWrapper'] = function ($testSoapContainer){
            $testSoapWrapper = $this->createMock(SoapWrapper::class);
            return $testSoapWrapper;
        };

        $testMsgModelContainer = $testApp->getContainer();

        $testMsgModelContainer['messageDetailsModel'] = function ($testMsgModelContainer){
            $testMsgDetailsModel = $this->createMock(MessageDetailsModel::class);
            return $testMsgDetailsModel;
        };

        $testDBContainer = new Container();
        $testApp->add($testDBContainer);

        $testDBContainer = $testApp->getContainer();
        $testDBContainer['databaseWrapper'] = function ($testDBContainer){
            $testDbConfig = $this->test_db_config;
            $testDBWrapper = new DatabaseWrapper();
            $testDBWrapper->setDatabaseConnectionSettings($testDbConfig);
            return $testDBWrapper;
        };

        $testSettingsContainer = $testApp->getContainer();
        $testSettingsContainer['settings'] = function ($testSettingsContainer){

            $testSettingsModel['pdo_settings'] = $this->test_db_config;

            return $testSettingsModel;
        };
        $this->testApp =$testApp;

    }

    public function testFetchMessages()
    {
        $testProcessMsg = new ProcessMessage();

       $testApp = $this->testApp;

        $this->assertNull($testProcessMsg->fetchMessages($testApp));

    }
    public function testReturnMessages()
    {
        $testProcessMsg = new ProcessMessage();

        $testApp = $this->testApp;

        $this ->assertIsArray($testProcessMsg->returnMessages($testApp));

    }
    public function testGetMessages()
    {
        $testProcessMsg = new ProcessMessage();

        $testApp = $this->testApp;

        $this->assertNull($testProcessMsg->getMessages($testApp));

    }
    public function testSendSmsReceipt()
    {
        $testProcessMsg = new ProcessMessage();

        $testApp = $this->testApp;

        $this->assertNull($testProcessMsg->sendSmsReceipt($testApp,""));

    }

    public function testSanitiseMessage()
    {
        $testProcessMsg = new ProcessMessage();

        $testValidator = new Validator();

        $testXmlParse = new XmlParser();

        $testXml = ("<messagerx><sourcemsisdn>447817814149</sourcemsisdn><destinationmsisdn>447817814149</destinationmsisdn><receivedtime>17/12/2019 11:18:10
            </receivedtime><bearer>SMS</bearer><messageref>0</messageref><message>{\"switch\":{\"1\":true,\"2\":false,\"3\":true,\"4\":false},
            \"fan\":true,\"heater\":33,\"keypad\":3,\"id\":\"18-3110-AS\"}</message></messagerx>");

        $testXmlParse->setXmlStringToParse($testXml);
        $testXmlParse->parseTheXmlString();

        $testXml = $testXmlParse->getParsedData();

        $this->assertFalse($testProcessMsg->sanitiseMessage($testXml, $testValidator));

    }




}
