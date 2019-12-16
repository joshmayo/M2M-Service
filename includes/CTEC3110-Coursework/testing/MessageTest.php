<?php
/**
 * MessageTest.php
 *
 * Unit Test for Message class
 *
 * @uses \M2MConnect\Message
 *
 * @author Joshua Mayo, Sophie Hughes, Kieran McCrory
 */

namespace M2MConnect;

use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{


    public function test__construct()
    {
        $testMessage = new Message();

       $this->assertStringContainsString("", $testMessage->getDestinationMsisn()," Newly constructed class should have an empty field ");
       $this->assertStringContainsString("", $testMessage->getSourceMsisdn(),"Newly constructed class should have an empty field");
       $this->assertNull($testMessage->getSwitch1());
       $this->assertNull($testMessage->getSwitch2());
       $this->assertNull($testMessage->getSwitch3());
       $this->assertNull($testMessage->getSwitch4());
       $this->assertNull($testMessage->getFan());
       $this->assertEquals(0, $testMessage->getHeater());
       $this->assertEquals(0, $testMessage->getKeypad());
       $this->assertNull($testMessage->getReceivedTime());

       var_dump($testMessage);

    }
    public function test__destruct()
    {

    }
    public function testGetFan()
    {

    }


    public function testGetHeater()
    {

    }

    public function testGetSwitch2()
    {

    }

    public function testGetSwitch3()
    {

    }

    public function testGetReceivedTime()
    {

    }

    public function testGetSwitch1()
    {

    }

    public function testGetSourceMsisdn()
    {

    }

    public function testGetSwitch4()
    {

    }
    public function testGetDestinationMsisn()
    {
        $messageTest = new Message();



    }

    public function testGetKeypad()
    {

    }
}
