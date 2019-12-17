<?php
/**
 * loginForm.php
 *
 * Form for authenticating users
 *
 * @author Joshua Mayo, Sophie Hughes, Kieran McCrory
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/login', function (Request $request, Response $response) use ($app) {

    $html_output = $this->view->render($response,
        'loginForm.html.twig',
        [
            'css_path' => CSS_PATH,
            'js_path' => JS_PATH,
            'landing_page' => LANDING_PAGE,
            'sendMessage_page' => 'sendMessage',
            'analytics_page' => 'analytics',
            'login_page' => 'login',
            'SignUp_page' => 'login',
            'method' => 'post',
            'action' => 'performLogin',
            'initial_input_box_value' => null,
            'page_title' => APP_NAME,
            'page_heading_1' => APP_NAME,
            'page_heading_2' => 'Sign in',
        ]
    );

    return $html_output;

})->setName('login');
