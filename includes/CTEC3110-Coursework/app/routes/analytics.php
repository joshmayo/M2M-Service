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

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$app->get('/analytics', function (Request $request, Response $response) use ($app) {

    $process_message = $app->getContainer()->get('processMessage');
    $process_message->getMessages($app);
    $message_list = $process_message->returnMessages($app);

    $chart_location = createChart($app, $message_list);

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
            'chart_location' => '../' . $chart_location
        ]
    );

    return $html_output;

})->setName('analytics');

function createChart($app, array $message_data)
{
    if (function_exists('xdebug_start_trace'))
    {
      xdebug_start_trace();
    }
    require_once 'libchart/classes/libchart.php';

    $messageChartModel = $app->getContainer()->get('messageAnalytics');

    $messageChartModel->setStoredMessageData($message_data);
    $messageChartModel->createLineChart();
    $chart_details = $messageChartModel->getLineChartDetails();

    if (function_exists('xdebug_stop_trace'))
    {
    xdebug_stop_trace();
    }

    return $chart_details;
}