<?php
/**
 * XmlParserTest.php
 *
 * Unit Tests for XmlParser class
 *
 * @uses \M2MConnect\XmlParser
 *
 * @author Joshua Mayo, Sophie Hughes, Kieran McCrory
 */
namespace M2MConnect;

use PHPUnit\Framework\TestCase;

class XmlParserTest extends TestCase
{
    protected $xmlTestString;

    protected function setUp(): void
    {
        $this->xmlTestString = `<messagerx><sourcemsisdn>447817814149</sourcemsisdn><destinationmsisdn>447817814149</destinationmsisdn><receivedtime>17/12/2019 11:18:10
            </receivedtime><bearer>SMS</bearer><messageref>0</messageref><message>{\"switch\":{\"1\":true,\"2\":false,\"3\":true,\"4\":false},
            \"fan\":true,\"heater\":33,\"keypad\":3,\"id\":\"18-3110-AS\"}</message></messagerx>`;
    }

    public function testSetXmlStringToParse()
    {
        $testXmlParser = new XmlParser();
        $testXmlParser->setXmlStringToParse($this->xmlTestString);
        $this->assertNull($testXmlParser->getXmlStringToParse());
        $this->assertSame($this->xmlTestString, $testXmlParser->getXmlStringToParse());

        $testXmlParser->parseTheXmlString();

    }

    public function testGetParsedData()
    {
        $testXmlParser = new XmlParser();
        $testXmlParser->setXmlStringToParse($this->xmlTestString);
        $testXmlParser->parseTheXmlString();
        $this->assertIsArray($testXmlParser->getParsedData());
    }
}
