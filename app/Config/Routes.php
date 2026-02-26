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

// Dashboard
$routes->get('/dashboard', 'DashboardController::index');

// Appointments
$routes->get('/appointments/create', 'AppointmentController::create');
$routes->post('/appointments/store', 'AppointmentController::store');
$routes->get('/appointments/confirmation/(:num)', 'AppointmentController::confirmation/$1');
$routes->post('/appointments/update-status/(:num)', 'AppointmentController::updateStatus/$1');
