<?php
/**
 * homepage.php
 *
 * display the check primes application homepage
 *
 * allows the user to enter a value for testing if prime
 *
 * Author: CF Ingrams
 * Email: <cfi@dmu.ac.uk>
 * Date: 18/10/2015
 *
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/', function (Request $request, Response $response) use ($app) {

    $message_list = getMessages($app);
    // var_dump($message_list);
//    $html_output = $this->view->render($response,
//    'homepageform.html.twig',
//    [
//      'css_path' => CSS_PATH,
//      'landing_page' => LANDING_PAGE,
//      'method' => 'post',
//      'action' => 'processcountrydetails', // this action no longer exists
//      'initial_input_box_value' => null,
//      'page_title' => APP_NAME,
//      'page_heading_1' => APP_NAME,
//      'page_heading_2' => 'Display details about a country',
//      'country_names' => $message_list,
//      'page_text' => 'Select a country name, then select the required information details',
//    ]
//    );

    //$processed_output = processOutput($app, $html_output);

    return true;

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
    var_dump($message_list);

    return $message_list;

}