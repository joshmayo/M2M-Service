<?php
/**
 * processLogout.php
 *
 * Route for processing user logging out.
 *
 * @author Joshua Mayo, Sophie Hughes, Kieran McCrory
 *
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/processLogout', function (Request $request, Response $response) use ($app) {

    if (isset($_SESSION['user']))
    {
        session_unset();
        session_destroy();

        $html_output = $this->view->render($response,
            'logoutResult.html.twig',
            [
                'css_path' => CSS_PATH,
                'js_path' => JS_PATH,
                'landing_page' => LANDING_PAGE,
                'sendMessage_page' => 'sendMessage',
                'analytics_page' => 'analytics',
                'auth_page' => isset($_SESSION['user']) ? 'processLogout' : 'login',
                'auth_text' => isset($_SESSION['user']) ? 'Sign out' : 'Sign in',
                'SignUp_page' => 'signUp',
                'page_title' => APP_NAME,
                'page_heading_1' => APP_NAME,
                'page_heading_2' => 'Sign Out',
                'result' => 'Successfully signed out',
            ]
        );

        return $html_output;
    }
    else {
        return $response->withRedirect(LANDING_PAGE);
    }


})->setName('processLogout');
