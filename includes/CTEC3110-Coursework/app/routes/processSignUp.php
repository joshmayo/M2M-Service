<?php
/**
 * processSignUp.php
 *
 * Handles and controls sign up requests..
 *
 * @author Joshua Mayo, Sophie Hughes, Kieran McCrory
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

/**
 * Handles the addition of cleaned parameters and ensures only cleaned params are sent to the server.
 */

$app->post('/processSignUp', function (Request $request, Response $response) use ($app) {

    $tainted_parameters = $request->getParsedBody();
    $cleaned_parameters = cleanupSignupParameters($app, $tainted_parameters);
    $hashed_password = hash_password($app, $cleaned_parameters['password']);
    $hashed_confirm = hash_password($app, $cleaned_parameters['passwordConfirm']);

    if ($hashed_password != null &&
        $hashed_confirm != null &&
        $cleaned_parameters['sanitised_username'] != false &&
        $cleaned_parameters['password'] == $cleaned_parameters['passwordConfirm']) {
        try {
            $database = $app->getContainer()->get('databaseWrapper');
            $db_conf = $app->getContainer()->get('settings');
            $database->setDatabaseConnectionSettings($db_conf['pdo_settings']);

            $database->addUser($cleaned_parameters['sanitised_username'], $hashed_password, 'user');

            return $html_output = $this->view->render($response,
                'signUpResult.html.twig',
                [
                    'landing_page' => LANDING_PAGE,
                    'css_path' => CSS_PATH,
                    'js_path' => JS_PATH,
                    'page_title' => APP_NAME,
                    'page_heading_1' => APP_NAME,
                    'page_heading_2' => 'Sign Up',
                    'sendMessage_page' => 'sendMessage',
                    'analytics_page' => 'analytics',
                    'login_page' => 'login',
                    'SignUp_page' => 'signUp',
                    'result' => 'Welcome, ' . $cleaned_parameters['sanitised_username'] . '!',
                ]);
        } catch (Exception $e) {
            $refused_message = 'That username is already taken, please try again.';
        }
    } else {
        $refused_message = 'Something was wrong with your details.';
    }

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
            'message' => $refused_message,
        ]
    );

    return $html_output;

})->setName('processSignUp');


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

function cleanupSignupParameters($app, $tainted_parameters)
{
    $cleaned_parameters = [];
    $validator = $app->getContainer()->get('validator');

    $tainted_username = $tainted_parameters['username'];
    $tainted_password = $tainted_parameters['password'];
    $tainted_confirm = $tainted_parameters['passwordConfirm'];

    $cleaned_parameters['password'] = $validator->validatePassword($tainted_password);
    $cleaned_parameters['passwordConfirm'] = $validator->validatePassword($tainted_confirm);
    $cleaned_parameters['sanitised_username'] = $validator->validateUsername($tainted_username);
    return $cleaned_parameters;
}

function encrypt($app, $cleaned_parameters)
{
    $libsodium_wrapper = $app->getContainer()->get('libSodiumWrapper');

    $encrypted = [];
    $encrypted['encrypted_username_and_nonce'] = $libsodium_wrapper->encrypt($cleaned_parameters['sanitised_username']);

    return $encrypted;
}

function encode($app, $encrypted_data)
{
    $base64_wrapper = $app->getContainer()->get('base64Wrapper');

    $encoded = [];
    $encoded['encoded_username'] = $base64_wrapper->encode_base64($encrypted_data['encrypted_username_and_nonce']['nonce_and_encrypted_string']);
    return $encoded;
}

/**
 * Uses the Bcrypt library with constants from settings.php to create hashes of the entered password
 *
 * @param $app
 * @param $password_to_hash
 * @return string
 */
function hash_password($app, $password_to_hash): string
{
    $bcrypt_wrapper = $app->getContainer()->get('bcryptWrapper');
    $hashed_password = $bcrypt_wrapper->createHashedPassword($password_to_hash);
    return $hashed_password;
}

/**
 * function both decodes base64 then decrypts the extracted cipher code
 *
 * @param $libsodium_wrapper
 * @param $base64_wrapper
 * @param $encoded
 * @return array
 */
function decrypt($app, $encoded): array
{
    $decrypted_values = [];
    $base64_wrapper = $app->getContainer()->get('base64Wrapper');
    $libsodium_wrapper = $app->getContainer()->get('libSodiumWrapper');

    $decrypted_values['username'] = $libsodium_wrapper->decrypt(
        $base64_wrapper,
        $encoded['encoded_username']
    );

    return $decrypted_values;
}