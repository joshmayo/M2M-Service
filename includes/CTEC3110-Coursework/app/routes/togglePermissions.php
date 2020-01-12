<?php
/**
 * Created by PhpStorm.
 * User: p16190097
 * Date: 12/01/2020
 * Time: 12:54
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/togglePermissions', function (Request $request, Response $response) use ($app) {

    if (isset($_SESSION['PERMISSIONS']) && $_SESSION['PERMISSIONS'] === '0') {

        $error = null;
        $user_id = $request->getParsedBody();

        $database = $app->getContainer()->get('databaseWrapper');
        $db_conf = $app->getContainer()->get('settings');
        $database->setDatabaseConnectionSettings($db_conf['pdo_settings']);

        try {
            $database->togglePrivilege($user_id['user']);
        }
        catch (exception $exception) {
            $error = $exception->getMessage();
        }

        if($error !== null) {
            $html_output = $this->view->render($response,
                'responseView.html.twig',
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
                    'page_heading_2' => 'An error has occurred',
                    'error_msg' => 'Users permissions could not be changed',
                ]
            );

            return $html_output;
        }

        $html_output = $this->view->render($response,
            'responseView.html.twig',
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
                'page_heading_2' => 'Success!',
                'error_msg' => 'Users permissions were changes successfully',
            ]
        );

        return $html_output;

    } else {
        return $response->withRedirect(LANDING_PAGE);
    }

})->setName('togglePermissions');