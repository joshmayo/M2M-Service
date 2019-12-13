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

        $messages = $messagedetails_model->getResult();

        $validator = $app->getContainer()->get('validator');
        $xml_parser = $app->getContainer()->get('xmlParser');
        $valid_resp = true;

        $valid_messages = [];

        foreach ($messages as $key => $message_xml) {
            $xml_parser->setXmlStringToParse($message_xml);
            $xml_parser->parseTheXmlString();
            $parsed_xml = $xml_parser->getParsedData();

            $validated_sourceMSISDN = $validator->validateMSISDN($parsed_xml['SOURCEMSISDN']);
            $validated_destinationMSISDN = $validator->validateMSISDN($parsed_xml['DESTINATIONMSISDN']);
            $validated_receivedTime = $validator->validateReceivedTime($parsed_xml['RECEIVEDTIME']);
            $validated_bearer = $validator->validateBearer($parsed_xml['BEARER']);
            $validated_messageRef = $validator->validateMessageRef($parsed_xml['MESSAGEREF']);
            $validated_message = $validator->validateMessage($parsed_xml['MESSAGE']);

            if (!$validated_sourceMSISDN ||
                !$validated_destinationMSISDN ||
                !$validated_receivedTime ||
                !$validated_bearer ||
                !$validated_messageRef ||
                !$validated_message) {

                $valid_resp = false;
                break;
            }
            else {
                $valid_messages[] = $parsed_xml;
            }
        }

        if ($valid_resp) {
            return $valid_messages;
        } else {
            return 'Failed api validation';
        }
    }

    function getMessages($app)
    {
        $message_detail_result = $this->fetchMessages($app);

        if (is_array($message_detail_result)) {

            foreach ($message_detail_result as $key => $message) {

                if (strpos($message['MESSAGE'], '18-3110-AS') !== false && strpos($message['MESSAGE'], 'invalid code')
                    ==
                    false) {

                    $parsed_json = json_decode($message['MESSAGE'], true);

                    $message = new \M2MConnect\Message(
                        $message['SOURCEMSISDN'],
                        $message['DESTINATIONMSISDN'],
                        $parsed_json['switch']['1'] == '' ? 0 : 1,
                        $parsed_json['switch']['2'] == '' ? 0 : 1,
                        $parsed_json['switch']['3'] == '' ? 0 : 1,
                        $parsed_json['switch']['4'] == '' ? 0 : 1,
                        $parsed_json['fan'] == '' ? 0 : 1,
                        $parsed_json['heater'],
                        $parsed_json['keypad'],
                        $message['RECEIVEDTIME']
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