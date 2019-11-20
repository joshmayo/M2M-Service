<?php
/**
 * class XmlParser
 *
 * Parses a given XML string and returns an associative array
 *
 */

namespace M2MConnect;

class XmlParser
{
    private $xml_parser;                              // handle to instance of the XML parser
    private $parsed_data;              // array holds extracted data
    private $element_name;                // store the current element name
    private $temporary_attributes = [];    // temporarily store tag attributes and values
    private $xml_string_to_parse;

    public function __construct()
    {
        $this->parsed_data = [];
    }

    // release retained memory
    public function __destruct()
    {
        xml_parser_free($this->xml_parser);
    }

    public function resetXmlParser()
    {
        $this->xml_parser = null;
    }

    public function setXmlStringToParse($xml_string_to_parse)
    {
        $this->xml_string_to_parse = $xml_string_to_parse;
    }

    public function getParsedData()
    {
        return $this->parsed_data;
    }

    public function parseTheXmlString()
    {
        $this->xml_parser = xml_parser_create();

        xml_set_object($this->xml_parser, $this);

        // assign functions to be called when a new element is entered and exited
        xml_set_element_handler($this->xml_parser, "open_element", "close_element");

        // assign the function to be used when an element contains data
        xml_set_character_data_handler($this->xml_parser, "process_element_data");

        $this->parseTheDataString();
    }

    // use the parser to step through the element tags
    private function parseTheDataString()
    {
        xml_parse($this->xml_parser, $this->xml_string_to_parse);
    }

    // process an open element event & store the tag name
    // extract the attribute names and values, if any
    private function open_element($parser, $element_name, $attributes)
    {
        $this->element_name = $element_name;
        if (sizeof($attributes) > 0) {
            foreach ($attributes as $att_name => $att_value) {
                $tag_att = $element_name . "." . $att_name;
                $this->temporary_attributes[$tag_att] = $att_value;
            }
        }
    }

    // process data from an element
    private function process_element_data($parser, $element_data)
    {
        $this->parsed_data[$this->element_name] = $element_data;
        if (sizeof($this->temporary_attributes) > 0) {
            foreach ($this->temporary_attributes as $tag_att_name => $tag_att_value) {
                $this->parsed_data[$tag_att_name] = $tag_att_value;
            }
            var_dump($this->temporary_attributes);
        }
    }

    // process a close element event
    private function close_element($parser, $element_name)
    {
        // do nothing here
    }
}