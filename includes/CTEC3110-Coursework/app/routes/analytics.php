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

    $line_chart = createChart($app, $message_list, 'line');
    $pie_chart = createChart($app, $message_list, 'pie');
    $bar_chart = createChart($app, $message_list, 'bar');

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
            'line_chart' => '../' . $line_chart,
            'pie_chart' => '../' . $pie_chart,
            'bar_chart' => '../' . $bar_chart
        ]
    );

    return $html_output;

})->setName('analytics');

function createChart($app, array $message_data, $type)
{
    require_once 'libchart/classes/libchart.php';

    $messageChartModel = $app->getContainer()->get('messageAnalytics');

    $messageChartModel->setStoredMessageData($message_data);

    if($type == 'line')
    {
        $messageChartModel->createLineChart();
    }
    else if($type == 'pie')
    {
        $messageChartModel->createPieChart();
    }
    else if($type == 'bar')
    {
        $messageChartModel->createBarChart();
    }

    $chart_details = $messageChartModel->getLineChartDetails();

    return $chart_details;
}