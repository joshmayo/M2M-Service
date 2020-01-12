<?php
/**
 * Created by PhpStorm.
 * User: p16190097
 * Date: 20/12/2019
 * Time: 15:40
 */

namespace M2MConnect;

use PHPUnit\Framework\TestCase;

class BcryptWrapperTest extends TestCase
{
    protected $falseString = "TestyMcTestface";
    protected $testString = "Tester#12";
    protected $hashedString = "$2y$12\$ZNrb2kvvDQGIsSq6Bw7NKetkOWzy5OalUxH2BpG0U6apBtvfzn7ma";
    protected $config;

    protected function setup(): void
    {
        $this->config = require_once("../app/settings.php");
    }

    public function testAuthenticatePassword()
    {
        $testBcryptWrapper = new BcryptWrapper();

        $this->assertIsBool($testBcryptWrapper->authenticatePassword($this->testString,
            $this->hashedString));
        $this->assertTrue($testBcryptWrapper->authenticatePassword($this->testString,
            $this->hashedString));
        $this->assertFalse($testBcryptWrapper->authenticatePassword($this->falseString,
            $this->hashedString));
    }

    public function testCreateHashedPassword()
    {
        $testBcryptWrapper = new BcryptWrapper();

        $this->assertNotEmpty($testBcryptWrapper->createHashedPassword($this->testString));
        $this->assertiSString($testBcryptWrapper->createHashedPassword($this->testString));
    }
}
