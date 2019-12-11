<?php

/**
 * analytics.php
 *
 * page for dispaying message analytics
 *
 * Author: Josh Mayo
 * Date: 02/12/2019
 *
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/analytics', function (Request $request, Response $response) use ($app) {

    $html_output = $this->view->render($response,
        'charts.html.twig',
        [
            'css_path' => CSS_PATH,
            'js_path' => JS_PATH,
            'landing_page' => LANDING_PAGE,
            'sendMessage_page' => 'sendMessage',
            'analytics_page' => 'analytics',
            'initial_input_box_value' => null,
            'page_title' => APP_NAME,
            'page_heading_1' => APP_NAME,
            'page_heading_2' => 'Analytics',
            'page_text' => 'M2M message Analytics', // no longer exists
        ]
    );

    return $html_output;

})->setName('analytics');