<?php
/**
 * processSendMessage.php
 *
 * Handles and controls sending of created messages on the page.
 *
 * @author Joshua Mayo, Sophie Hughes, Kieran McCrory
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

/**
 * Handles the addition of cleaned parameters and ensures only cleaned params are sent to the server.
 */

$app->post('/processSendMessage', function (Request $request, Response $response) use ($app) {

    $tainted_parameters = $request->getParsedBody();
    $cleaned_parameters = cleanupParameters($app, $tainted_parameters);

    if ($cleaned_parameters != false) {
        $cleaned_parameters['id'] = '18-3110-AS';
        $message_body = json_encode($cleaned_parameters);
        $message_detail_result = send($app, $message_body);
    } else {
        $message_detail_result = 'Please submit valid commands only';
    }

    $html_output = $this->view->render($response,
        'sendMessageResult.html.twig',
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
            'page_title' => APP_NAME,
            'page_heading_1' => APP_NAME,
            'page_heading_2' => 'Send Message Result',
            'message' => $message_detail_result,
        ]
    );

    return $html_output;
})->setName('processSendMessage');

/**
 * Sends the message to the server.
 *
 * @uses \M2MConnect\SoapWrapper
 * @uses \M2MConnect\MessageDetailsModel
 *
 * @param $app
 *
 * @param $payload
 *
 * @return string - Returns a message if sending succeeds. Returns an error message if unsuccessful.
 */

function send($app, $payload)
{
    $soap_wrapper = $app->getContainer()->get('soapWrapper');
    $messagedetails_model = $app->getContainer()->get('messageDetailsModel');
    $messagedetails_model->setSoapWrapper($soap_wrapper);

    try {
        $messagedetails_model->sendMessage($payload);
        $result = 'Message sent successfully';
    } catch (Exception $error) {
        $result = $error->getMessage();
    }
    return $result;
}

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

function cleanupParameters($app, $tainted_parameters)
{
    $cleaned_parameters = [];
    $validated_keypad_code = false;
    $validated_heater_code = false;
    $validated_switch_1 = false;
    $validated_switch_2 = false;
    $validated_switch_3 = false;
    $validated_switch_4 = false;
    $validated_fan = false;


    $validator = $app->getContainer()->get('validator');

    if (isset($tainted_parameters['keypad'])) {
        $tainted_keypad = $tainted_parameters['keypad'];
        $validated_keypad_code = $validator->validateKeypadCode($tainted_keypad);
    }
    if (isset($tainted_parameters['heater'])) {
        $tainted_heater = $tainted_parameters['heater'];
        $validated_heater_code = $validator->validateHeaterCode($tainted_heater);
    }

    if (isset($tainted_parameters['fan'])) {
        $tainted_fan = $tainted_parameters['fan'];
        $validated_fan = $validator->validateSwitch($tainted_fan);
    }

    if (isset($tainted_parameters['switch1'])) {
        $tainted_switch = $tainted_parameters['switch1'];
        $validated_switch_1 = $validator->validateSwitch($tainted_switch);
    }

    if (isset($tainted_parameters['switch2'])) {
        $tainted_switch = $tainted_parameters['switch2'];
        $validated_switch_2 = $validator->validateSwitch($tainted_switch);
    }

    if (isset($tainted_parameters['switch3'])) {
        $tainted_switch = $tainted_parameters['switch3'];
        $validated_switch_3 = $validator->validateSwitch($tainted_switch);
    }

    if (isset($tainted_parameters['switch4'])) {
        $tainted_switch = $tainted_parameters['switch4'];
        $validated_switch_4 = $validator->validateSwitch($tainted_switch);
    }

    if (is_int($validated_keypad_code) &&
        is_int($validated_heater_code) &&
        is_bool($validated_switch_1) &&
        is_bool($validated_switch_2) &&
        is_bool($validated_switch_3) &&
        is_bool($validated_switch_4) &&
        is_bool($validated_fan)) {
        $cleaned_parameters['switch']['1'] = $validated_switch_1;
        $cleaned_parameters['switch']['2'] = $validated_switch_2;
        $cleaned_parameters['switch']['3'] = $validated_switch_3;
        $cleaned_parameters['switch']['4'] = $validated_switch_4;
        $cleaned_parameters['fan'] = $validated_fan;
        $cleaned_parameters['heater'] = $validated_heater_code;
        $cleaned_parameters['keypad'] = $validated_keypad_code;
    } else {
        return false;
    }
    return $cleaned_parameters;
}