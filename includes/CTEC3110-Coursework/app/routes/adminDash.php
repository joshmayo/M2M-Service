<?php
/**
 * adminDash.php
 *
 * Route for displaying admin dashboard.
 *
 * @author Joshua Mayo, Sophie Hughes, Kieran McCrory
 *
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/adminDash', function (Request $request, Response $response) use ($app) {

    if (isset($_SESSION['PERMISSIONS']) && $_SESSION['PERMISSIONS'] === '0') {
        $html_output = $this->view->render($response,
            'adminDash.html.twig',
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
                'page_title' => APP_NAME,
                'page_heading_1' => APP_NAME,
                'page_heading_2' => 'Administrator Dashboard',
            ]
        );

        return $html_output;
    } else {
        return $response->withRedirect(LANDING_PAGE);
    }

})->setName('adminDash');
