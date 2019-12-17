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

    $encrypted = encrypt($app, $cleaned_parameters);
    $hashed_password = hash_password($app, $cleaned_parameters['password']);
    $encoded = encode($app, $encrypted);
    $decrypted = decrypt($app, $encoded);

    $database = $app->getContainer()->get('databaseWrapper');
    $db_conf = $app->getContainer()->get('settings');
    $database->setDatabaseConnectionSettings($db_conf['pdo_settings']);

    $insertion_successful = $database->addUser($cleaned_parameters['sanitised_username'], $hashed_password, 'user');

    var_dump($insertion_successful);

    $html_output =  $this->view->render($response,
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
            'username' => $tainted_parameters['username'],
            'password' => $tainted_parameters['password'],
            'sanitised_username' => $cleaned_parameters['sanitised_username'],
            'cleaned_password' => $cleaned_parameters['password'],
            'hashed_password' => $hashed_password,
            'libsodium_version' => SODIUM_LIBRARY_VERSION,
            'nonce_value_username' => $encrypted['encrypted_username_and_nonce']['nonce'],
            'encrypted_username' => $encrypted['encrypted_username_and_nonce']['encrypted_string'],
            'encoded_username' => $encoded['encoded_username'],
            'decrypted_username' => $decrypted['username'],
        ]);

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
    $taited_password = $tainted_parameters['password'];

    $cleaned_parameters['password'] = $validator->validateUsername($taited_password);
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