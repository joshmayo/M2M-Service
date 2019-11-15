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
    '/processcountrydetails',
    function(Request $request, Response $response) use ($app)
    {
        $validated_country = false;
        $validated_detail = false;
        $country_detail_result = [];
        $comment = '';

        $tainted_parameters = $request->getParsedBody();
        $cleaned_parameters = cleanupParameters($app, $tainted_parameters);
        $country_details_result = getCountryDetails($app, $cleaned_parameters);
        $validated_country_details = validateDownloadedData($app, $country_details_result);
        var_dump($validated_country_details);

        $html_output = $this->view->render($response,
            'display_result.html.twig',
            [
                'css_path' => CSS_PATH,
                'landing_page' => LANDING_PAGE,
                'initial_input_box_value' => null,
                'page_title' => APP_NAME,
                'page_heading_1' => APP_NAME,
                'page_heading_2' => 'Result',
                'country_name' => $validated_country,
                'detail' => '',
                'result' => $comment,
            ]);

        $processed_output = processOutput($app, $html_output);

        return $processed_output;
    });

function cleanupParameters($app, $tainted_parameters)
{
    $cleaned_parameters = [];
    $validated_country_code = false;
    $validated_detail = false;

    $validator = $app->getContainer()->get('validator');

    if (isset($tainted_parameters['country']))
    {
        $tainted_country = $tainted_parameters['country'];
        $validated_country_code = $validator->validateCountryCode($tainted_country);
    }
    if (isset($tainted_parameters['detail']))
    {
        $tainted_detail = $tainted_parameters['detail'];
        $validated_detail = $validator->validateDetailType($tainted_detail);
    }

    if (($validated_country_code != false) && ($validated_detail != false))
    {
        $cleaned_parameters['country'] = $validated_country_code;
        $cleaned_parameters['detail'] = $validated_detail;
    }
    return $cleaned_parameters;
}

function validateDownloadedData($app, $tainted_data)
{
    $cleaned_data = '';

    if (is_string($tainted_data) == true)
    {
        $validator = $app->getContainer()->get('validator');
        $cleaned_data = $validator->validateDownloadedData($tainted_data);
    }
    else
    {
        $cleaned_data = $tainted_data;
    }

    return $cleaned_data;
}

function getCountryDetails($app, $cleaned_parameters)
{
    $country_detail_result = [];
    $soap_wrapper = $app->getContainer()->get('soapWrapper');

    $countrydetails_model = $app->getContainer()->get('countryDetailsModel');
    $countrydetails_model->setSoapWrapper($soap_wrapper);

    $countrydetails_model->setParameters($cleaned_parameters);
    $countrydetails_model->performDetailRetrieval();
    $country_detail_result = $countrydetails_model->getResult();

    return $country_detail_result;
}