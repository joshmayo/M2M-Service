<?php
/**
 * updateMessageTable.php
 *
 * Handles and controls the display and refresh of the sent messages table.
 *
 * @uses \M2MConnect\ProcessMessage
 *
 * @author Joshua Mayo, Sophie Hughes, Kieran McCrory
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/updateTable', function (Request $request, Response $response) use ($app) {

    $process_message = $app->getContainer()->get('processMessage');

    $fetch_result = $process_message->getMessages($app);

    if ($fetch_result) {
        $response->getBody()->write($fetch_result);
        return $response->withStatus(500)
            ->withHeader('Content-Type', 'text/html');
    }

    $message_list = $process_message->returnMessages($app);

    if (!is_array($message_list)) {
        $response->getBody()->write($message_list);
        return $response->withStatus(500)
            ->withHeader('Content-Type', 'text/html');
    }

    $response->getBody()->write(json_encode($message_list));
    return $response->withStatus(200)
        ->withHeader('Content-Type', 'application/json');

})->setName('updateTable');;
