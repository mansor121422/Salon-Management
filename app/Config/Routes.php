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

// Staff Management
$routes->get('/staff', 'Staff::index');
$routes->get('/staff/create', 'Staff::create');
$routes->post('/staff/store', 'Staff::store');
$routes->get('/staff/edit/(:num)', 'Staff::edit/$1');
$routes->post('/staff/update/(:num)', 'Staff::update/$1');
$routes->get('/staff/delete/(:num)', 'Staff::delete/$1');
