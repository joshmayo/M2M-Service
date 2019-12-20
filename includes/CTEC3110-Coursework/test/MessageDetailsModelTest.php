<?php
/**
 * MessageDetailsModelTest.php
 *
 * Unit Tests for MessageDetailsModel class
 *
 * @uses \M2MConnect\MessageDetailsModel
 *
 * @author Joshua Mayo, Sophie Hughes, Kieran McCrory
 */

namespace M2MConnect;

use PHPUnit\Framework\TestCase;

class MessageDetailsModelTest extends TestCase
{

    protected function setUp(): void
    {
        $this->config = require_once("../app/settings.php");
    }
    public function test__construct()
    {
        $testMessageModel = new MessageDetailsModel();

        $this->assertIsObject($testMessageModel);
        $this->assertNotEmpty($testMessageModel->getLog());

    }

    public function testSetSoapWrapper()
    {
        $testSoapWrapper = new SoapWrapper();

        $testMessageModel= new MessageDetailsModel();

        $this->assertNull($testMessageModel->setSoapWrapper($testSoapWrapper));
    }

    public function testRetrieveMessages()
    {
        $testMessageModel = new MessageDetailsModel();

        $testSoapWrapper = $this->createMock(SoapWrapper::class);


        $testMessageModel->setSoapWrapper($testSoapWrapper);

        $this->assertNull($testMessageModel->retrieveMessages());

    }

    public function testSendMessage()
    {

        $testMessageModel = new MessageDetailsModel();

        $testSoapWrapper = $this->createMock(SoapWrapper::class);


        $testMessageModel->setSoapWrapper($testSoapWrapper);

        $this->assertNull($testMessageModel->sendMessage());



    }








}
