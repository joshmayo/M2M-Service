<?php
/**
 * Created by PhpStorm.
 * User: cfi
 * Date: 20/11/15
 * Time: 14:01
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post(
  '/processtemperatureconversion',
  function(Request $request, Response $response) use ($app)
  {
    $arr_tainted_params = $request->getParsedBody();

    $validator = $this->get('validator');
    $tempconv_model = $this->get('tempconv_model');

    $tainted_temperature = $arr_tainted_params['temperature'];
    $validated_temperature = $validator->validate_temperature($tainted_temperature);
    $tainted_tempunit = $arr_tainted_params['fromunit'];
    $validated_fromunit = $validator->validate_unit_type($tainted_tempunit);
    $tainted_tempunit = $arr_tainted_params['tounit'];
    $validated_tounit = $validator->validate_unit_type($tainted_tempunit);

    $tempconv_model->set_conversion_parameters(
      $validated_temperature,
      $validated_fromunit,
      $validated_tounit
    );

    $tempconv_model->perform_temperature_conversion();
    $temperature_conversion_result = $tempconv_model->get_result();

    if ($temperature_conversion_result === false)
    {
      $temperature_conversion_result = 'not available';
    }

    $arr_temperature_units = TEMP_UNITS;

    return $this->view->render($response,
      'displaystoreddata.html.twig',
      [
        'css_path' => CSS_PATH,
        'landing_page' => LANDING_PAGE,
        'initial_input_box_value' => null,
        'page_title' => APP_NAME,
        'page_heading_1' => APP_NAME,
        'page_heading_2' => 'Result',

        'temperature' => $validated_temperature,
        'fromunit' => $arr_temperature_units[$validated_fromunit],
        'tounit' => $arr_temperature_units[$validated_tounit],
        'result' => $temperature_conversion_result,
      ]);
  });
