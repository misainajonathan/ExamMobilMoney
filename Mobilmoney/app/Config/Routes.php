<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

$routes->get('/login', 'Login::index');
$routes->post('/login', 'Login::authenticate');
$routes->group('client', ['filter' => 'session'], static function (RouteCollection $routes): void {
	$routes->get('/', static fn (): string => 'Client area');
});
