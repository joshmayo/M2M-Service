<?php
/**
 * ProcessMessage.php
 *
 * Business logic class for handling message input and output.
 *
 * @author Joshua Mayo, Sophie Hughes, Kieran McCrory
 *
 */

namespace M2MConnect;

class ProcessMessage
{

    /**
     * @uses MessageDetailsModel
     * @uses Validator
     * @uses XmlParser
     *
     * @param $app
     *
     * Calls the retreiveMessage function in MessageDetailsModel and validates against message rules
     * with Validator and finally transforms to JSON via XmlParser.
     *
     *
     * @return array|string - Returns a list of validated and JSON formatted messages.
     *
     * Will return an error if the messages fail to validate.
     */
    function fetchMessages($app)
    {
        $soap_wrapper = $app->getContainer()->get('soapWrapper');

        $messagedetails_model = $app->getContainer()->get('messageDetailsModel');
        $messagedetails_model->setSoapWrapper($soap_wrapper);

        $messagedetails_model->retrieveMessages();

        $messages = $messagedetails_model->getResult();

        $valid_messages = [];

        if (!empty($messages)) {
            $validator = $app->getContainer()->get('validator');
            $xml_parser = $app->getContainer()->get('xmlParser');

            foreach ($messages as $key => $message_xml) {
                $xml_parser->setXmlStringToParse($message_xml);
                $xml_parser->parseTheXmlString();
                $parsed_xml = $xml_parser->getParsedData();

                $safe_message = $this->sanitiseMessage($parsed_xml, $validator);

                if ($safe_message != false) {
                    $valid_messages[] = $safe_message;
                }
            }

            return $valid_messages;
        }
    }

    /**
     *
     * Function to sanitise each message for malicious content or illegal parameter removal.
     *
     * @param $message
     *
     * @param $validator
     *
     * @return string|boolean - Returns the message if validation is successful. Returns a false statement if any
     * validation fails.
     */

    function sanitiseMessage($message, $validator)
    {
        $validated_sourceMSISDN = $validator->validateMSISDN($message['SOURCEMSISDN']);
        $validated_destinationMSISDN = $validator->validateMSISDN($message['DESTINATIONMSISDN']);
        $validated_receivedTime = $validator->validateReceivedTime($message['RECEIVEDTIME']);
        $validated_bearer = $validator->validateBearer($message['BEARER']);
        $validated_messageRef = $validator->validateMessageRef($message['MESSAGEREF']);
        $validated_message = $validator->validateMessage($message['MESSAGE']);

        if ($validated_sourceMSISDN === false ||
            $validated_destinationMSISDN === false ||
            $validated_receivedTime === false ||
            $validated_bearer === false ||
            $validated_messageRef === false ||
            $validated_message === false) {

            return false;
        } else {
            return $message;
        }
    }

    /**
     * Function to get all messages and add each one to the database.
     *
     * @uses DatabaseWrapper#
     *
     * @param $app
     *
     * @return array|bool|string - Will Return the array of messages if successful. Will Return false if no messages
     * are found. Will return any database error if database interactions fail.
     *
     */
    function getMessages($app)
    {
        $message_detail_result = $this->fetchMessages($app);

        if (is_array($message_detail_result) && !empty($message_detail_result)) {

            foreach ($message_detail_result as $key => $message) {

                if (strpos($message['MESSAGE'], '18-3110-AS') !== false && strpos($message['MESSAGE'],
                        'invalid code') === false) {

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
                        $new_message = $database->addMessage($message);

                        if (isset($new_message[0]['@new_message']) && $new_message[0]['@new_message'] != 0) {
                            $this->sendSmsReceipt($app, $new_message[0]['@new_message']);
                        }
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

    /**
     * Returns all messages stored in the database
     *
     * @uses DatabaseWrapper
     *
     * @param $app
     *
     * @return string
     */

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

    function sendSmsReceipt($app, $msisdn)
    {
        $messagedetails_model = $app->getContainer()->get('messageDetailsModel');

        $messagedetails_model->sendMessage('Message received! Group 18_3110_AS', $msisdn);

    }
}