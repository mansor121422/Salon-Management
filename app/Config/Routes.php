<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Authentication Routes
$routes->get('/login', 'AuthController::login');
$routes->post('/login', 'AuthController::authenticate');
$routes->get('/logout', 'AuthController::logout');

// Receptionist Dashboard (Unified)
$routes->get('/receptionist', 'ReceptionistController::index');
$routes->get('/receptionist/dashboard', 'ReceptionistController::index');

// Receptionist Appointments (Unified)
$routes->get('/receptionist/appointments/create', 'ReceptionistController::create');
$routes->post('/receptionist/appointments/store', 'ReceptionistController::store');
$routes->get('/receptionist/appointments/confirmation/(:num)', 'ReceptionistController::confirmation/$1');
$routes->post('/receptionist/appointments/update-status/(:num)', 'ReceptionistController::updateStatus/$1');
$routes->get('/receptionist/appointments/all', 'ReceptionistController::getAllAppointments');

// Legacy routes for backward compatibility (redirect to receptionist routes)
$routes->get('/appointments/create', 'ReceptionistController::create');
$routes->post('/appointments/store', 'ReceptionistController::store');
$routes->get('/appointments/confirmation/(:num)', 'ReceptionistController::confirmation/$1');
$routes->post('/appointments/update-status/(:num)', 'ReceptionistController::updateStatus/$1');

