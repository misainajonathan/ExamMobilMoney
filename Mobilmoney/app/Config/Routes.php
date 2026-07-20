<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', function() {
    return redirect()->to(site_url('login'));
});

$routes->group('admin', function ($routes) {
    $routes->get('/', 'Admin::dashboard');
    $routes->get('prefixes', 'Admin::prefixes');
    $routes->post('addPrefix', 'Admin::addPrefix');
    $routes->get('deletePrefix/(:num)', 'Admin::deletePrefix/$1');
    $routes->get('frais', 'Admin::frais');
    $routes->post('updateFrais', 'Admin::updateFrais');
    $routes->get('gains', 'Admin::gains');
    $routes->get('comptes', 'Admin::comptes');
});

$routes->add('login', 'Auth::login');
$routes->get('auth/logout', 'Auth::logout');

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