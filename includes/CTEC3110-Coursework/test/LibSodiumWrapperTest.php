<?php
/**
 * Created by PhpStorm.
 * User: p16190097
 * Date: 20/12/2019
 * Time: 17:02
 */

namespace M2MConnect;

use PHPUnit\Framework\TestCase;

class LibSodiumWrapperTest extends TestCase
{
    protected $testString = "howbowda";
    protected $testEncryptString = "Binary String: 0x32ffe8530a4ff0d9b1a034befc6841a34d90340b61c60087";
    protected $config;

    protected function setup(): void
    {
        $this->config = require_once("../app/settings.php");
    }

    public function testEncrypt()
    {
        $testLibSodium = new LibSodiumWrapper();

        $this->assertIsArray($testLibSodium->encrypt($this->testString));
        $this->assertNotEmpty($testLibSodium->encrypt($this->testString)['nonce']);
        $this->assertNotEmpty($testLibSodium->encrypt($this->testString)['encrypted_string']);
        $this->assertNotEmpty($testLibSodium->encrypt($this->testString)['nonce_and_encrypted_string']);
    }
}
