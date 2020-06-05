<?php

use Faker\Factory;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

$app->get('/', function (Request $request, Response $response) use ($entityManager, $cache) {
	if ($cache->contains('all_users')) {
		$cachedUsers = $cache->fetch('all_users');

		$response->getBody()->write(json_encode($cachedUsers, JSON_PRETTY_PRINT));
		return $response->withAddedHeader('Content-Type', 'application/json');
	}

	$users = $entityManager->getRepository('user')->findAll();

	if (count($users) >= 1) {
		
		$resp = array_map(function ($item) { return $item->export(); }, $users);

		$cache->save('all_users', $resp);

		$response->getBody()->write(json_encode($resp, JSON_PRETTY_PRINT));
	} else {
		$response->getBody()->write(json_encode(['message' => 'Users not found, go to /generate'], JSON_PRETTY_PRINT));
	}

	return $response->withAddedHeader('Content-Type', 'application/json');
});

$app->get('/generate/{count}', function (Request $request, Response $response) use ($entityManager, $cache) {
	$count = (int)$request->getAttribute('count', 3);
	if ($count === 0) $count = 3;

	$faker = Factory::create();
	for ($i = 0; $i < $count; $i++) {
		$user = new User();
		$user->setName($faker->firstName);
		$user->setDateInsert();
		$entityManager->persist($user);
	}
	$entityManager->flush();

	$usersDB = $entityManager->getRepository('user')->findAll();

	$users = array_map(function ($item) { return $item->export(); }, $usersDB);

	$cache->save('all_users', $users);
	$response->getBody()->write(json_encode(['message' => "$count users created"]));
	return $response->withAddedHeader('Content-Type', 'application/json');
});