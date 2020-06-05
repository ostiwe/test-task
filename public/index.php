<?php
require_once dirname(__DIR__) . '/bootstrap.php';

use DI\Container;
use Slim\Factory\AppFactory;

$container = new Container();

AppFactory::setContainer($container);

$app = AppFactory::create();

require_once dirname(__DIR__) . '/routes.php';


$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);

$app->run();