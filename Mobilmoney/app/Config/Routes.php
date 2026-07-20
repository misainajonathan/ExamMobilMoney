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