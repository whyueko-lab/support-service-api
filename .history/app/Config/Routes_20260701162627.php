<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/training', 'Training::index');

$routes->get('/training/generate', 'Training::generate');

$routes->get('/training/train', 'Training::train');

$routes->get('/training/test', 'Training::test');

$routes->get('ai/train', 'AI::train');

$routes->get('')


$routes->get('/', 'Dashboard::index');
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
$routes->get('/register', 'Auth::register');
$routes->post('/register/process', 'Auth::processRegister');
$routes->get('/dashboard', 'Dashboard::index');
$routes->get('/dashboard/tickets', 'Dashboard::tickets');
$routes->post('/dashboard/tickets/update-status/(:num)', 'Dashboard::updateTicketStatus/$1');
$routes->get('/dashboard/tickets/create', 'Dashboard::createTicketForm');
$routes->post('/dashboard/tickets/store', 'Dashboard::storeTicket');
$routes->get('/dashboard/tickets/detail/(:num)', 'Dashboard::ticketDetail/$1');
$routes->get('/dashboard/notifications', 'Dashboard::notifications');
$routes->get('/dashboard/ratings', 'Dashboard::ratings');
$routes->get('api/rating/ticket/(:num)', 'Api\RatingController::getByTicket/$1');
$routes->get('/dashboard/reports/sla', 'Dashboard::slaReport');
$routes->get('/dashboard/reports/sla/download-pdf', 'Dashboard::downloadSlaReportPdf');
$routes->get('/login', 'Auth::login');
$routes->post('/login/process', 'Auth::processLogin');
$routes->get('/logout', 'Auth::logout');
$routes->get('/dashboard/users', 'Dashboard::users');
$routes->get('/dashboard/users/create', 'Dashboard::createUserForm');
$routes->post('/dashboard/users/store', 'Dashboard::storeUser');
$routes->get('/dashboard/users/edit/(:num)', 'Dashboard::editUserForm/$1');
$routes->post('/dashboard/users/update/(:num)', 'Dashboard::updateUser/$1');
$routes->get('/dashboard/users/delete/(:num)', 'Dashboard::deleteUser/$1');
$routes->get('/dashboard/users/toggle-status/(:num)', 'Dashboard::toggleUserStatus/$1');

$routes->options('(:any)', static function () {
    return service('response')
        ->setHeader('Access-Control-Allow-Origin', '*')
        ->setHeader('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, Authorization')
        ->setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->setStatusCode(200);
});


//$routes->post('/api/fix-password', 'Api\AuthController::fixPassword');// ini untuk reset password admin

