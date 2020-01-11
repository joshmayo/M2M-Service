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
            'auth_page' => isset($_SESSION['user']) ? 'processLogout' : 'login',
            'auth_text' => isset($_SESSION['user']) ? 'Sign out' : 'Sign in',
            'admin_dash' => isset($_SESSION['PERMISSIONS']) && $_SESSION['PERMISSIONS'] === '0' ? 'adminDash' : null,
            'SignUp_page' => 'signUp',
            'method' => 'post',
            'action' => 'processSignUp',
            'page_title' => APP_NAME,
            'page_heading_1' => APP_NAME,
            'page_heading_2' => 'Sign Up',
            'message' => null,
            'logo_path' => '/CTEC3110-Coursework/media/android-chrome-512x512.png',
        ]
    );

    return $html_output;

})->setName('signUp');
