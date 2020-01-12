<?php

// Register component on container
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(
        $container['settings']['view']['template_path'],
        $container['settings']['view']['twig'],
        [
            'debug' => true // This line should enable debug mode
        ]
    );

    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()),
        '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

    return $view;
};

$container['validator'] = function ($container) {
    $validator = new \M2MConnect\Validator();
    return $validator;
};

$container['soapWrapper'] = function ($container) {
    $validator = new \M2MConnect\SoapWrapper();
    return $validator;
};

$container['databaseWrapper'] = function ($container) {
    $database_wrapper_handle = new \M2MConnect\DatabaseWrapper();
    return $database_wrapper_handle;
};

$container['messageDetailsModel'] = function ($container) {
    $model = new \M2MConnect\MessageDetailsModel();
    return $model;
};

$container['messageDbModel'] = function ($container) {
    $model = new \M2MConnect\MessageDbModel();
    return $model;
};

$container['message'] = function ($container) {
    $model = new \M2MConnect\Message();
    return $model;
};

$container['processMessage'] = function ($container) {
    $model = new \M2MConnect\ProcessMessage();
    return $model;
};

$container['messageAnalytics'] = function ($container) {
    $model = new \M2MConnect\MessageAnalyticsModel();
    return $model;
};

$container['xmlParser'] = function ($container) {
    $model = new \M2MConnect\XmlParser();
    return $model;
};

$container['loggerWrapper'] = function ($container) {
    $logging_wrapper = new Monolog\Logger('logger');
    return $logging_wrapper;
};

$container['libSodiumWrapper'] = function ($container) {
    $wrapper = new \M2MConnect\LibSodiumWrapper();
    return $wrapper;
};

$container['base64Wrapper'] = function ($container) {
    $wrapper = new \M2MConnect\Base64Wrapper();
    return $wrapper;
};

$container['bcryptWrapper'] = function ($container) {
    $wrapper = new \M2MConnect\BcryptWrapper();
    return $wrapper;
};
