<?php
/**
 * class XmlParser
 *
 * Parses a given XML string and returns an associative array
 *
 * @author Joshua Mayo, Sophie Hughes, Kieran McCrory
 *
 */

namespace M2MConnect;

class XmlParser
{
    private $xml_parser;
    private $parsed_data;
    private $element_name;
    private $temporary_attributes = [];
    private $xml_string_to_parse;

    public function __construct()
    {
        $this->parsed_data = [];
    }

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

        xml_set_element_handler($this->xml_parser, "open_element", "close_element");

        xml_set_character_data_handler($this->xml_parser, "process_element_data");

        $this->parseTheDataString();
    }

    private function parseTheDataString()
    {
        xml_parse($this->xml_parser, $this->xml_string_to_parse);
    }

    /**
     * Opens each element to obtain the attributes.
     *
     * @param $parser
     *
     * @param $element_name
     *
     * @param $attributes
     */

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

    /**
     *
     * Converts each element's data to a the temporary_attributes
     *
     * @param $parser
     * 
     * @param $element_data
     */

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

    private function close_element($parser, $element_name)
    {
    }
}