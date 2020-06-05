<?php

require_once __DIR__ . "/vendor/autoload.php";

use Doctrine\Common\Cache\PhpFileCache;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

$isDevMode = true;
$proxyDir = NULL;
$cache = new PhpFileCache(
	__DIR__ . '/doctrine/cache'
);

$useSimpleAnnotationReader = false;
$config = Setup::createAnnotationMetadataConfiguration([__DIR__ . "/doctrine/Models"],
	$isDevMode, $proxyDir, $cache, $useSimpleAnnotationReader);

$conn = [
	'dbname' => 'doctr',
	'user' => 'apiblog',
	'password' => 'apiblog',
	'host' => '127.0.0.1',
	'driver' => 'pdo_mysql',

];

$entityManager = EntityManager::create($conn, $config);