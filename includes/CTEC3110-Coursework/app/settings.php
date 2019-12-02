<?php
/**
 * Created by PhpStorm.
 * User: slim
 * Date: 13/10/17
 * Time: 12:33
 */

ini_set('display_errors', 'On');
ini_set('html_errors', 'On');
ini_set('xdebug.trace_output_name', 'messaging_details.%t');

define('DIRSEP', DIRECTORY_SEPARATOR);

$url_root = $_SERVER['SCRIPT_NAME'];
$url_root = implode('/', explode('/', $url_root, -1));
$css_path = $url_root . '/css/standard.css';
define('CSS_PATH', $css_path);
define('APP_NAME', 'CTEC3110-Coursework');
define('LANDING_PAGE', $_SERVER['SCRIPT_NAME']);
define('M2M_USER', '19_SophieHughes');
define('M2M_PASS', 'P161776552019php');
define('MSISDN', '7817814149');
define('COUNTRY_CODE', '44');

$wsdl = 'https://m2mconnect.ee.co.uk/orange-soap/services/MessageServiceByCountry?wsdl';
define('WSDL', $wsdl);

$settings = [
  "settings" => [
    'displayErrorDetails' => true,
    'addContentLengthHeader' => false,
    'mode' => 'development',
    'debug' => true,
    'class_path' => __DIR__ . '/src/',
    'view' => [
      'template_path' => __DIR__ . '/templates/',
      'twig' => [
        'cache' => false,
        'auto_reload' => true,
      ]],
      'pdo_settings' => [
          'rdbms' => 'mysql',
          'host' => 'localhost',
          'db_name' => 'coursework_db',
          'port' => '3306',
          'user_name' => 'coursework_user',
          'user_password' => 'coursework_user_pass',
          'charset' => 'utf8',
          'collation' => 'utf8_unicode_ci',
          'options' => [
              PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
              PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
              PDO::ATTR_EMULATE_PREPARES   => true,
          ]],
  ],
];

return $settings;
