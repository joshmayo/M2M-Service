<?php
/**
 * ProcessMessage.php
 *
 * Business logic class for handling the messages.
 */

namespace M2MConnect;


class ProcessMessage
{
    function fetchMessages($app)
    {
        $soap_wrapper = $app->getContainer()->get('soapWrapper');

        $messagedetails_model = $app->getContainer()->get('messageDetailsModel');
        $messagedetails_model->setSoapWrapper($soap_wrapper);

        $messagedetails_model->retrieveMessages();
        return $messagedetails_model->getResult();
    }

    function getMessages($app)
    {
        $message_detail_result = $this->fetchMessages($app);

        if (is_array($message_detail_result)) {

            $xml_parser = $app->getContainer()->get('xmlParser');

            foreach ($message_detail_result as $key => $message_xml) {

                if (strpos($message_xml, '18-3110-AS') !== false && strpos($message_xml, 'invalid code') == false) {

                    $xml_parser->setXmlStringToParse($message_xml);
                    $xml_parser->parseTheXmlString();
                    $parsed_xml = $xml_parser->getParsedData();
                    $parsed_json = json_decode($parsed_xml['MESSAGE'], true);

                    $message = new \M2MConnect\Message(
                        $parsed_xml['SOURCEMSISDN'],
                        $parsed_xml['DESTINATIONMSISDN'],
                        $parsed_json['switch']['1'] == '' ? 0 : 1,
                        $parsed_json['switch']['2'] == '' ? 0 : 1,
                        $parsed_json['switch']['3'] == '' ? 0 : 1,
                        $parsed_json['switch']['4'] == '' ? 0 : 1,
                        $parsed_json['fan'] == '' ? 0 : 1,
                        $parsed_json['heater'],
                        $parsed_json['keypad'],
                        $parsed_xml['RECEIVEDTIME']
                    );

                    $database = $app->getContainer()->get('databaseWrapper');
                    $db_conf = $app->getContainer()->get('settings');
                    $database->setDatabaseConnectionSettings($db_conf['pdo_settings']);

                    try {
                        $database->addMessage($message);
                    } catch (Exception $error) {
                        return $error->getMessage();
                    }
                }
            }

            return false;

        } else {
            return $message_detail_result;
        }

    }

    function returnMessages($app)
    {
        $database = $app->getContainer()->get('databaseWrapper');
        $db_conf = $app->getContainer()->get('settings');
        $database->setDatabaseConnectionSettings($db_conf['pdo_settings']);

        try {
            $message_list = $database->getMessages();
        } catch (Exception $error) {
            return $error->getMessage();
        }

        return $message_list;
    }
}