<?php

/**
 * signUp.php
 *
 * Display a form to allow the user to create an account.
 *
 * @author Joshua Mayo, Sophie Hughes, Kieran McCrory
 *
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/signUp', function (Request $request, Response $response) use ($app) {


    $html_output = $this->view->render($response,
        'signUpForm.html.twig',
        [
            'css_path' => CSS_PATH,
            'js_path' => JS_PATH,
            'landing_page' => LANDING_PAGE,
            'sendMessage_page' => 'sendMessage',
            'analytics_page' => 'analytics',
            'login_page' => 'login',
            'SignUp_page' => 'signUp',
            'method' => 'post',
            'action' => 'processSignUp',
            'page_title' => APP_NAME,
            'page_heading_1' => APP_NAME,
            'page_heading_2' => 'Sign Up',
        ]
    );

    return $html_output;

})->setName('signUp');
