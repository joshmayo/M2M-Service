<?php
/**
 * homepage.php
 *
 * display the Message application homepage
 *
 * Author: Josh Mayo
 * Date: 02/12/2019
 *
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/', function (Request $request, Response $response) use ($app) {

    getMessages($app);
    $message_list = returnMessages($app);

    $html_output = $this->view->render($response,
    'homepagetable.html.twig',
    [
      'css_path' => CSS_PATH,
      'landing_page' => LANDING_PAGE,
      'sendMessage_page' => 'sendMessage',
      'analytics_page' => 'analytics',
      'page_title' => APP_NAME,
      'page_heading_1' => APP_NAME,
      'page_heading_2' => 'Messages',
      'message_list' => $message_list,
      'page_text' => 'M2M messages view', // no longer exists
    ]
    );

    $processed_output = processOutput($app, $html_output);

    return $processed_output;

})->setName('homepage');

function processOutput($app, $html_output)
{
    $process_output = $app->getContainer()->get('processOutput');
    $html_output = $process_output->processOutput($html_output);
    return $html_output;
}

function getMessages($app)
{
    $message_list = [];

    $soap_wrapper = $app->getContainer()->get('soapWrapper');

    $messagedetails_model = $app->getContainer()->get('messageDetailsModel');
    $messagedetails_model->setSoapWrapper($soap_wrapper);

    $messagedetails_model->retrieveMessages();
    $message_detail_result = $messagedetails_model->getResult();

    $xml_parser = $app->getContainer()->get('xmlParser');
    foreach ($message_detail_result as $key => $message_xml)
    {
        if(strpos($message_xml, '18-3110-AS') !== false && strpos($message_xml, 'invalid code') == false)
        {
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
            $settings = $db_conf['pdo_settings'];

            $messagedetails_model->addMessage($message, $database, $settings);
        }

    }

    return $message_list;

}

function returnMessages($app)
{
    $message_list = [];

    $messagedetails_model = $app->getContainer()->get('messageDetailsModel');
    $database = $app->getContainer()->get('databaseWrapper');
    $db_conf = $app->getContainer()->get('settings');
    $settings = $db_conf['pdo_settings'];

    $message_list = $messagedetails_model->getMessagesFromDatabase($database, $settings);

    var_dump($message_list);

    return $message_list;
}