<?php
/**
 * Base64WrapperTest.php
 *
 * Unit Tests for Base64Wrapper class
 *
 * @uses \M2MConnect\Base64Wrapper
 *
 * @author Joshua Mayo, Sophie Hughes, Kieran McCrory
 */

namespace M2MConnect;

use PHPUnit\Framework\TestCase;

class Base64WrapperTest extends TestCase
{
    protected $testString = "TestString";
    protected $encodedString = "VGVzdFN0cmluZw==";


    public function testEncode_base64()
    {
        $testB64Wrapper = new Base64Wrapper();

        $this->assertIsString($testB64Wrapper->encode_base64($this->testString));
        $this->assertSame($this->encodedString,($testB64Wrapper->encode_base64($this->testString)));
        $this->assertEmpty($testB64Wrapper->encode_base64(""));

    }

    public function testDecode_base64()
    {
        $testB64Wrapper = new Base64Wrapper();

        $this->assertIsString($testB64Wrapper->decode_base64($this->encodedString));
        $this->assertSame($this->testString,$testB64Wrapper->decode_base64($this->encodedString));
        $this->assertEmpty($testB64Wrapper->decode_base64(""));

    }
}
