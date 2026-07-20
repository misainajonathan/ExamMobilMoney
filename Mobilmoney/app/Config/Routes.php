<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', static fn (): \CodeIgniter\HTTP\RedirectResponse => redirect()->to('/client'));

$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::login');
$routes->get('/logout', 'Auth::logout');

$routes->group('client', ['filter' => 'session'], static function (RouteCollection $routes): void {
	$routes->get('/', 'Client::index');
	$routes->get('depot', 'Client::depot');
	$routes->post('depot', 'Client::depot');
	$routes->get('retrait', 'Client::retrait');
	$routes->post('retrait', 'Client::retrait');
	$routes->get('transfert', 'Client::transfert');
	$routes->post('transfert', 'Client::transfert');
	$routes->get('historique', 'Client::historique');
});
