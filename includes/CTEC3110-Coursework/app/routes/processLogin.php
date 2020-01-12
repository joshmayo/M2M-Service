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
    $cleaned_parameters = cleanupLoginParameters($app, $tainted_parameters);

    $database = $app->getContainer()->get('databaseWrapper');
    $db_conf = $app->getContainer()->get('settings');
    $database->setDatabaseConnectionSettings($db_conf['pdo_settings']);

    try {

        $stored_user = $database->getUser($cleaned_parameters['username']);
    }
    catch (Exception $e) {
        $stored_user = false;
    }

    if($stored_user != false && auth_password($app, $cleaned_parameters['password'], $stored_user['hashed_password'])) {

        $ecryption = $app->getContainer()->get('libSodiumWrapper');

        $encrypted_user = $ecryption->encrypt($cleaned_parameters['username']);
        $_SESSION['user'] = $encrypted_user['encrypted_string'];
        $_SESSION['PERMISSIONS'] = $stored_user['privilege'];
        return $response->withRedirect(LANDING_PAGE);
    }
    else {
        $html_output = $this->view->render($response,
            'loginForm.html.twig',
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
                'action' => 'performLogin',
                'initial_input_box_value' => null,
                'page_title' => APP_NAME,
                'page_heading_1' => APP_NAME,
                'page_heading_2' => 'Sign In',
                'logo_path' => '/CTEC3110-Coursework/media/android-chrome-512x512.png',
                'error_text' => 'Error logging in',
            ]
        );

        return $html_output;
    }

})->setName('performLogin');

/**
 * Sanitises all inputted parameters before sending.
 *
 * By default will fail if any values are not set.
 *
 * @uses \M2MConnect\Validator
 *
 * @param $app
 *
 * @param $tainted_parameters
 *
 * @return array|bool
 */
function cleanupLoginParameters($app, $tainted_parameters)
{
    $cleaned_parameters = [];
    $validator = $app->getContainer()->get('validator');

    $tainted_username = $tainted_parameters['username'];
    $tainted_password = $tainted_parameters['password'];

    $cleaned_parameters['password'] = $validator->validatePassword($tainted_password);
    $cleaned_parameters['username'] = $validator->validateUsername($tainted_username);
    return $cleaned_parameters;
}

/**
 * Uses the Bcrypt library with constants from settings.php to auth the given password against that hash stored in the database
 *
 * @param $app
 * @param $password_to_check
 * @param $password_check_against
 * @return string
 */
function auth_password($app, $password_to_check, $password_check_against)
{
    $bcrypt_wrapper = $app->getContainer()->get('bcryptWrapper');
    $match = $bcrypt_wrapper->authenticatePassword($password_to_check, $password_check_against);
    return $match;
}