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

    $message_list = getMessages($app);

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
      'page_text' => 'Select a country name, then select the required information details', // no longer exists
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
    foreach ($message_detail_result as $key => $message) {
        $xml_parser->setXmlStringToParse($message);
        $xml_parser->parseTheXmlString();
        array_push($message_list, $xml_parser->getParsedData());
    }
    //var_dump($message_list);

    return $message_list;

}