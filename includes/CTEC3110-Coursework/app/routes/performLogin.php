<?php
/**
 * Created by PhpStorm.
 * User: p16190097
 * Date: 17/12/2019
 * Time: 12:11
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/performLogin',  function (Request $request, Response $response) use ($app) {

    $tainted_parameters = $request->getParsedBody();
    var_dump($tainted_parameters);
    $cleaned_parameters = cleanupLoginParameters($app, $tainted_parameters);

    if(true) {
        return $this->response->withRedirect('index.php');
    }
    else {
        $app->halt(403, 'Login Failed');
    }

})->setName('performLogin');

function cleanupLoginParameters($app, $tainted_parameters)
{
    $cleaned_parameters = [];
    $validated_username = false;
    $validated_password = false;

    $validator = $app->getContainer()->get('validator');

    if (isset($tainted_parameters['username']))
    {
        $tainted_username = $tainted_parameters['username'];
        //$validated_username = $validator->validateKeypadCode($tainted_username);
    }
    if (isset($tainted_parameters['password']))
    {
        $tainted_password = $tainted_parameters['password'];
        //$validated_password = $validator->validateHeaterCode($tainted_password);
    }

    if (!$validated_username === false &&
        !$validated_password === false)
    {
        $cleaned_parameters['username'] = $validated_username;
        $cleaned_parameters['password'] = $validated_password;
    }
    else{
        return false;
    }
    return $cleaned_parameters;
}