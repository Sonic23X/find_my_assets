<?php namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('Index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

/** Landing **/
$routes->get('/', 'Home::Index');
$routes->post('/contacto', 'Home::Contact');

/** Auth **/
$routes->get('/ingreso', 'Auth::Login');
$routes->post('/user-email', 'Auth::UserExist');
$routes->post('/acceso', 'Auth::Access');
$routes->post('/recovery-password', 'Auth::RecoveryPassword');

/** Register **/
$routes->get('/registro', 'Auth::Register');
$routes->post('/nuevo', 'Auth::New');
$routes->get('/confirmacion/(:any)', 'Auth::ValidateEmail/$1');

/** Logout **/
$routes->get('/salir', 'Auth::Logout');

/** Backoffice - Inicio **/
$routes->get('/dashboard', 'Dashboard::Index');

/** AJax - scanner **/
$routes->post('/activos/search', 'Activo::SearchActivo');
$routes->post('/activos/new', 'Activo::NewActivo');
$routes->post('/activos/updateInfo', 'Activo::UpdateInfoActivo');

/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need to it be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
