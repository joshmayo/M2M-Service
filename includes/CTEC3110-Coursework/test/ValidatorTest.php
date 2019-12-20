<?php
/**
 * ValidatorTest.php
 *
 * Message Validation Tests
 *
 * @author Joshua Mayo, Sophie Hughes, Kieran McCrory
 *
 */

namespace M2MConnect;

use PHPUnit\Framework\TestCase;
use DateTime;

class ValidatorTest extends TestCase
{
    public function test__construct()
    {
        $testValidator = new Validator();
        $this->assertInstanceOf(Validator::class, $testValidator);

    }

    public function testValidateHeaterCode()
    {
    $testValidator = new Validator();

    $this->assertSame(5,$testValidator->validateHeaterCode(5));
    $this->assertSame("invalid number",$testValidator->validateHeaterCode(1000));
    $this->assertSame("invalid number",$testValidator->validateHeaterCode(-1));
    $this->assertSame("invalid number",$testValidator->validateHeaterCode("blue"));

    }
    public function testValidateKeypadCode()
{
    $testValidator = new Validator();

    $this->assertSame("invalid number",$testValidator->validateKeypadCode(11));
    $this->assertSame(3,$testValidator->validateKeypadCode(3));
    $this->assertSame("invalid number",$testValidator->validateKeypadCode(-11));
    $this->assertSame("invalid number",$testValidator->validateKeypadCode("a"));

    }
    public function testValidateSwitch()
    {
        $testValidator = new Validator();

        $this->assertSame(false, $testValidator->validateSwitch(false));
        $this->assertSame(true, $testValidator->validateSwitch(true));
        $this->assertSame(true, $testValidator->validateSwitch("on"));
        $this->assertSame("invalid switch",$testValidator->validateSwitch(3));

    }
    public function testValidateMSISDN()
    {
        $testValidator = new Validator();

        $this->assertSame("000000000000", $testValidator->validateMSISDN("000000000000"));
        $this->assertSame(false, $testValidator->validateMSISDN("0000000000001"));
        $this->assertSame(false, $testValidator->validateMSISDN(000000000000));
        $this->assertSame(false, $testValidator->validateMSISDN("0"));
        $this->assertSame(false, $testValidator->validateMSISDN(true));

    }
    public function testValidateReceivedTime()
    {
        $testValidator = new Validator();

        $this->assertSame("17/12/2019 00:00:00",$testValidator->validateReceivedTime("17/12/2019 00:00:00"));
        $this->assertFalse($testValidator->validateReceivedTime("17-12-2019 0120:00:00"));
        $this->assertFalse($testValidator->validateReceivedTime(""));
        $this->assertFalse($testValidator->validateReceivedTime(100));
        $this->assertFalse($testValidator->validateReceivedTime(true));
        $this->assertFalse($testValidator->validateReceivedTime("abc"));
    }


    public function testValidateMessage()
    {
        $testValidator = new Validator();
        $messageResp = "{&#34;switch&#34;:{&#34;1&#34;:true,&#34;2&#34;:false,&#34;3&#34;:true,&#34;4&#34;:false},&#34;fan&#34;:true,&#34;heater&#34;:33,&#34;keypad&#34;:3,&#34;id&#34;:&#34;18-3110-AS&#34;}";

        $this->assertSame($messageResp, $testValidator->validateMessage($messageResp));

    }

    public function testValidateBearer()
    {
        $testValidator = new Validator();

        $this->assertSame("SMS", $testValidator->validateBearer("SMS"));
        $this->assertSame("GPRS", $testValidator->validateBearer("GPRS"));
        $this->assertFalse($testValidator->validateBearer(1));
        $this->assertFalse($testValidator->validateBearer(120));
        $this->assertFalse($testValidator->validateBearer("ABCDEFG"));
    }

    public function testValidateMessageRef()
    {
        $testValidator = new Validator();
        $this->assertSame(65, $testValidator->validateMessageRef(65));
        $this->assertFalse($testValidator->validateMessageRef(1000000000000000000));
        $this->assertFalse($testValidator->validateMessageRef(-110));
        $this->assertFalse($testValidator->validateMessageRef("three"));
        $this->assertFalse($testValidator->validateMessageRef(true));

    }

    public function testValidatePassword()
    {
        $testValidator = new Validator();
        $badPw = "Password01";
        $goodPw = "!Password_01";

        $this->assertFalse($testValidator->validatePassword($badPw));
        $this->assertSame($goodPw, $testValidator->validatePassword($goodPw));
        $this->assertFalse($testValidator->validatePassword("!Password_01000000000000000000000000000000"));
        $this->assertFalse($testValidator->validatePassword("!Pa_01"));
    }

    public function testValidateUsername()
    {
        $testValidator = new Validator();
        $goodUN = "Testusername1";
        $badUN = "!!!!!!!!!!!!!!!";

        $this->assertFalse($testValidator->validateUsername($badUN));
        $this->assertSame($goodUN, $testValidator->validateUsername($goodUN));
        $this->assertFalse($testValidator->validateUsername("Te"));
        $this->assertFalse($testValidator->validateUsername("TestusernameThatIsfarTooLongForThisField"));

    }




}
