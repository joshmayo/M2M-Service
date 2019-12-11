<?php
/**
 * Created by PhpStorm.
 * User: p16190097
 * Date: 11/12/2019
 * Time: 09:24
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/updateTable', function (Request $request, Response $response) use ($app) {

    $process_message = $app->getContainer()->get('processMessage');

    $fetch_result = $process_message->getMessages($app);
    $message_list = $process_message->returnMessages($app);

    if ($fetch_result || !is_array($message_list)) {
        return false;
    } else {
        return json_encode($message_list);
    }

})->setName('updateTable');;
