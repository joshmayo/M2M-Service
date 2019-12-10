<?php

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

$app->get('/sendMessage', function (Request $request, Response $response) use ($app) {

    $html_output = $this->view->render($response,
        'sendMessageForm.html.twig',
        [
            'css_path' => CSS_PATH,
            'js_path' => JS_PATH,
            'landing_page' => LANDING_PAGE,
            'sendMessage_page' => 'sendMessage',
            'analytics_page' => 'analytics',
            'method' => 'post',
            'action' => 'processSendMessage',
            'initial_input_box_value' => null,
            'page_title' => APP_NAME,
            'page_heading_1' => APP_NAME,
            'page_heading_2' => 'Send Message',
            'page_text' => 'Send a message to M2M Service', // no longer exists
        ]
    );

    $processed_output = processOutput($app, $html_output);

    return $processed_output;

})->setName('sendMessage');
