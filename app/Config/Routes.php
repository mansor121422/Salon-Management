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

// Receptionist Dashboard 
$routes->get('/receptionist', 'ReceptionistController::index');
$routes->get('/receptionist/dashboard', 'ReceptionistController::index');

// Receptionist Appointments (Unified)
$routes->get('/receptionist/appointments/create', 'ReceptionistController::create');
$routes->post('/receptionist/appointments/store', 'ReceptionistController::store');
$routes->get('/receptionist/appointments/confirmation/(:num)', 'ReceptionistController::confirmation/$1');
$routes->post('/receptionist/appointments/update-status/(:num)', 'ReceptionistController::updateStatus/$1');
$routes->get('/receptionist/appointments/edit/(:num)', 'ReceptionistController::edit/$1');
$routes->post('/receptionist/appointments/update/(:num)', 'ReceptionistController::update/$1');
$routes->get('/receptionist/appointments/all', 'ReceptionistController::getAllAppointments');

// Admin Dashboard
$routes->get('/admin', 'AdminController::index');
$routes->get('/admin/dashboard', 'AdminController::index');

// Staff Dashboard
$routes->get('/staff', 'StaffController::index');
$routes->get('/staff/dashboard', 'StaffController::index');
$routes->get('/staff/appointments', 'StaffController::appointments');
$routes->get('/staff/appointments/data', 'StaffController::getAppointments');
$routes->post('/staff/appointments/update-status/(:num)', 'StaffController::updateStatus/$1');

// Owner Dashboard
$routes->get('/owner', 'OwnerController::index');
$routes->get('/owner/dashboard', 'OwnerController::index');

// Admin User Management
$routes->get('/admin/users/create', 'AdminController::createUser');
$routes->post('/admin/users/store', 'AdminController::storeUser');
$routes->get('/admin/users/edit/(:num)', 'AdminController::editUser/$1');
$routes->post('/admin/users/update/(:num)', 'AdminController::updateUser/$1');
$routes->post('/admin/users/toggle/(:num)', 'AdminController::toggleUserStatus/$1');
$routes->get('/admin/users/all', 'AdminController::getAllUsers');
$routes->get('/admin/activity', 'AdminController::getActivityData');
$routes->get('/admin/users/details/(:num)', 'AdminController::getUserDetails/$1');

// Legacy routes for backward compatibility (redirect to receptionist routes)
$routes->get('/appointments/create', 'ReceptionistController::create');
$routes->post('/appointments/store', 'ReceptionistController::store');
$routes->get('/appointments/confirmation/(:num)', 'ReceptionistController::confirmation/$1');
$routes->post('/appointments/update-status/(:num)', 'ReceptionistController::updateStatus/$1');

