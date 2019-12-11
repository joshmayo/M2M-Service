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

    //$fetch_result = getMessages($app);
    //$message_list = returnMessages($app);

    //return json_encode($message_list);
    return true;
});
