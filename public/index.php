<?php

error_reporting(E_ALL);
ini_set('display_errors', 'on');

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
require __DIR__ . "/../vendor/autoload.php";
require __DIR__ . "/../src/config/db.php";
$config['displayErrorDetails'] = true;

$app = new \Slim\App(["settings" => $config]);
$container = $app->getContainer();

// customer routes
require __DIR__ . '/../src/routes/customers.php';

$app->run();
