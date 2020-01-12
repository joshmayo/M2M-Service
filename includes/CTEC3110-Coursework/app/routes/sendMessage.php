<?php

/**
 * sendMessage.php
 *
 * Form for sending messages to SOAP M2M API
 *
 * @author Joshua Mayo, Sophie Hughes, Kieran McCrory
 *
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/sendMessage', function (Request $request, Response $response) use ($app) {

    if (isset($_SESSION['user']))
    {
        $html_output = $this->view->render($response,
            'sendMessageForm.html.twig',
            [
                'css_path' => CSS_PATH,
                'js_path' => JS_PATH,
                'landing_page' => LANDING_PAGE,
                'sendMessage_page' => 'sendMessage',
                'analytics_page' => 'analytics',
                'auth_page' => isset($_SESSION['user']) ? 'processLogout' : 'login',
                'auth_text' => isset($_SESSION['user']) ? 'Sign out' : 'Sign in',
                'admin_dash' => isset($_SESSION['PERMISSIONS']) && ($_SESSION['PERMISSIONS'] === '0' || $_SESSION['PERMISSIONS'] === '2') ? 'adminDash' : null,
                'SignUp_page' => 'signUp',
                'method' => 'post',
                'action' => 'processSendMessage',
                'initial_input_box_value' => null,
                'page_title' => APP_NAME,
                'page_heading_1' => APP_NAME,
                'page_heading_2' => 'Send Message',
            ]
        );

        return $html_output;
    }
    else {
        return $response->withRedirect('login');
    }


})->setName('sendMessage');
