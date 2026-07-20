<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

$routes->group('admin', function ($routes) {
    // Tableau de bord principal
    $routes->get('dashboard', 'Admin::dashboard');

    // Gestion des préfixes
    $routes->get('prefixes', 'Admin::prefixes');
    $routes->post('addPrefix', 'Admin::addPrefix');
    $routes->get('deletePrefix/(:num)', 'Admin::deletePrefix/$1');

    // Barèmes de frais
    $routes->get('frais', 'Admin::frais');
    $routes->post('updateFrais', 'Admin::updateFrais');

    // Situation des gains de l'opérateur
    $routes->get('gains', 'Admin::gains');

    // Situation des comptes clients
    $routes->get('comptes', 'Admin::comptes');
});

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
