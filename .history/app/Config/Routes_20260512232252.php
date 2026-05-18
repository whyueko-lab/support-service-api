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
$routes->get('/api/notifications/user/(:num)', 'Api\NotificationController::byUser/$1');
$routes->get('/api/notifications/unread/(:num)', 'Api\NotificationController::unreadCount/$1');
$routes->post('/api/notifications/read/(:num)', 'Api\NotificationController::markAsRead/$1');
$routes->post('/api/notifications/read-all/(:num)', 'Api\NotificationController::markAllAsRead/$1');
$routes->get('/api/dashboard/admin', 'Api\DashboardController::admin');
$routes->get('/api/dashboard/teknisi/(:num)', 'Api\DashboardController::teknisi/$1');
$routes->get('/api/tickets/check-overdue', 'Api\TicketController::checkOverdue');
$routes->get('/api/tickets/user/(:num)', 'Api\TicketController::byUser/$1');
$routes->get('/api/tickets/status/(:segment)', 'Api\TicketController::byStatus/$1');
$routes->get('/api/tickets/priority/(:segment)', 'Api\TicketController::byPriority/$1');
$routes->post('/api/register', 'Api\AuthController::register');
$routes->post('/api/login', 'Api\AuthController::login');