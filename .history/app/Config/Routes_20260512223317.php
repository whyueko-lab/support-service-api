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