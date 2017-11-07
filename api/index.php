<?php

/**
 * @author José María Peirano <peirano357@gmail.com>
 */
error_reporting(1);
define('SERVER_SUB_FOLDER', '');
include_once($_SERVER['DOCUMENT_ROOT'] . SERVER_SUB_FOLDER . '/settings.php');
include_once (VENDOR_ROOT . '/autoload.php');

$app = new \Slim\Slim();
$app->config('debug', false);

include_once(VENDOR_ROOT . '/slim/slim/Slim/Middleware.php');
include_once(SLIM_ROOT . '/Middleware/HttpBasicAuth.php');

\Slim\Slim::registerAutoloader();

$app->add(new \HttpBasicAuth());

include_once(SLIM_ROOT . '/controller/Controller.api.php');

include_once(SLIM_ROOT . '/controller/User.api.php');
$user = new \API\Controller\User($app);

include_once(SLIM_ROOT . '/controller/GameSession.api.php');
$gamesession = new \API\Controller\GameSession($app);

include_once(SLIM_ROOT . '/views/API_View.php');
$app->view(new \Api\View\Api_View($app));

$app->notFound(function ($format = 'json') use ($app) {
    $app->view()->setResponse(array(), 404, 'Not found');
    $app->render($format);
});

$app->run();