<?php
/**
 * Created by PhpStorm.
 * User: p16190097
 * Date: 02/12/2019
 * Time: 12:56
 */

/**
 * sendMessage.php
 *
 * Form for sending messages to SOAP M2M API
 *
 * Author: Josh Mayo
 * Date: 02/12/2019
 *
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/analytics', function (Request $request, Response $response) use ($app) {

    $html_output = $this->view->render($response,
        'sendMessageForm.html.twig',
        [
            'css_path' => CSS_PATH,
            'landing_page' => LANDING_PAGE,
            'sendMessage_page' => 'sendMessage',
            'analytics_page' => 'analytics',
            'method' => 'post',
            'action' => 'processcountrydetails',
            'initial_input_box_value' => null,
            'page_title' => APP_NAME,
            'page_heading_1' => APP_NAME,
            'page_heading_2' => 'Analytics',
            'page_text' => 'Select a country name, then select the required information details', // no longer exists
        ]
    );

    $processed_output = processOutput($app, $html_output);

    return $processed_output;

})->setName('analytics');