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

    $stored_hash = $database->getHash($cleaned_parameters['username']);

    if(auth_password($app, $cleaned_parameters['password'], $stored_hash)) {
        var_dump('Successful login');
    }
    else {
        var_dump('Error logging in');
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