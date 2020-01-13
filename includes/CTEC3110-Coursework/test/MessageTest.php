<?php
/**
 * MessageTest.php
 *
 * Unit Tests for Message class
 *
 * @uses \M2MConnect\Message
 *
 * @author Joshua Mayo, Sophie Hughes, Kieran McCrory
 */

namespace M2MConnect;

use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{
//https://symfonycasts.com/screencast/phpunit/full-mock-example

    public function test__construct()
    {
        $testMessage = new Message();

        $this->assertStringContainsString("", $testMessage->getDestinationMsisn(),
            " Newly constructed class should have an empty field ");
        $this->assertStringContainsString("", $testMessage->getSourceMsisdn(),
            "Newly constructed class should have an empty field");
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

    public function testGetSourceMsisdn()
    {
        $testMessage = new Message("447817814149", "447817814149", 1, 0,
            1, 0, 1, 60, 1, "2019-12-16 15:00:10");

        $this->assertEquals("447817814149", $testMessage->getSourceMsisdn());
        var_dump($testMessage);


    }

    public function testGetDestinationMsisn()
    {
        $testMessage = new Message("447817814149", "447817814149", 1, 0,
            1, 0, 1, 60, 1, "2019-12-16 15:00:10");

        $this->assertEquals("447817814149", $testMessage->getDestinationMsisn());
        var_dump($testMessage);
    }

    public function testGetFan()
    {
        $testMessage = new Message("447817814149", "447817814149", 1, 0,
            1, 0, 1, 60, 1, "2019-12-16 15:00:10");
        $this->assertEquals(1, $testMessage->getFan());

        var_dump($testMessage);
    }


    public function testGetHeater()
    {
        $testMessage = new Message("447817814149", "447817814149", 1, 0,
            1, 0, 1, 60, 1, "2019-12-16 15:00:10");

        $this->assertEquals(60, $testMessage->getHeater());

        var_dump($testMessage);
    }

    public function testGetSwitch1()
    {
        $testMessage = new Message("447817814149", "447817814149", 1, 0,
            1, 0, 1, 60, 1, "2019-12-16 15:00:10");

        $this->assertEquals(1, $testMessage->getSwitch1());

        var_dump($testMessage);
    }

    public function testGetSwitch2()
    {
        $testMessage = new Message("447817814149", "447817814149", 1, 0,
            1, 0, 1, 60, 1, "2019-12-16 15:00:10");

        $this->assertEquals(0, $testMessage->getSwitch2());

        var_dump($testMessage);
    }

    public function testGetSwitch3()
    {
        $testMessage = new Message("447817814149", "447817814149", 1, 0,
            1, 0, 1, 60, 1, "2019-12-16 15:00:10");

        $this->assertEquals(1, $testMessage->getSwitch3());


        var_dump($testMessage);
    }

    public function testGetSwitch4()
    {
        $testMessage = new Message("447817814149", "447817814149", 1, 0,
            1, 0, 1, 60, 1, "2019-12-16 15:00:10");

        $this->assertEquals(0, $testMessage->getSwitch4());

        var_dump($testMessage);
    }

    public function testGetReceivedTime()
    {
        $testMessage = new Message("447817814149", "447817814149", 1, 0,
            1, 0, 1, 60, 1, "2019-12-16 15:00:10");
        $this->assertEquals("2019-12-16 15:00:10", $testMessage->getReceivedTime());

        var_dump($testMessage);

    }


    public function testGetKeypad()
    {
        $testMessage = new Message("447817814149", "447817814149", 1, 0,
            1, 0, 1, 60, 1, "2019-12-16 15:00:10");

        $this->assertEquals(1, $testMessage->getKeypad());
        var_dump($testMessage);
    }
}
