<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/test-db', 'TestDatabase::index');
$routes->get('/api/users', 'Api\UserController::index');
$routes->get('/api/tickets', 'Api\TicketController::index');
$routes->post('/api/tickets/create', 'Api\TicketController::create');
$routes->post('/api/tickets/update-status/(:num)', 'Api\TicketController::updateStatus/$1');
$routes->get('/api/ratings', 'Api\RatingController::index');
$routes->post('/api/ratings/create', 'Api\RatingController::create');
$routes->get('/api/ratings/summary', 'Api\RatingController::summary');