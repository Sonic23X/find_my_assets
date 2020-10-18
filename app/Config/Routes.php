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

/** Backoffice - Perfil **/
$routes->get('/perfil', 'User::Index');

/** AJAX - Inicio **/
$routes->get('/dashboard/data', 'Dashboard::getData');

/** AJAX - scanner **/
$routes->get('/activos/getFormData', 'Activo::GetDataForm');
$routes->post('/activos/search', 'Activo::SearchActivo');
$routes->post('/activos/validateNew', 'Activo::ValidateActivo');
$routes->post('/activos/new', 'Activo::NewActivo');
$routes->post('/activos/updateInfo', 'Activo::UpdateInfoActivo');
$routes->post('/activos/setGeo', 'Activo::SetCoordenadas');
$routes->get('/activos/getImageFront/(:alphanum)', 'Activo::GetImageFront/$1');
$routes->get('/activos/getImageLeft/(:alphanum)', 'Activo::GetImageLeft/$1');
$routes->get('/activos/getImageRight/(:alphanum)', 'Activo::GetImageRight/$1');
$routes->post('/activos/setImage', 'Activo::SetImage');
$routes->post('/activos/deleteImage', 'Activo::DeleteImage');

/** AJAX - inventary **/
$routes->get('/inventario/getFormData', 'Inventary::GetDataForm');
$routes->get('/inventario/getItems', 'Inventary::SearchItemList');
$routes->get('/inventario/getDraftInfo/(:num)', 'Inventary::SearchItemInfo/$1');
$routes->get('/inventario/getDraftDetails/(:num)', 'Inventary::SearcItemDetails/$1');
$routes->get('/inventario/getDraftBuyDetails/(:num)', 'Inventary::SearcItemBuyDetails/$1');
$routes->post('/inventario/saveDraftBuyDetails', 'Inventary::SaveDraftBuyDetails');
$routes->post('/inventario/draftToActivo', 'Inventary::draftToActivo');
$routes->post('/inventario/draftDelete', 'Inventary::draftDelete');
$routes->get('/inventario/concilar/(:num)', 'Inventary::SearchItemsConciliar/$1');
$routes->post('/inventario/concilarActivo', 'Inventary::SearchDataConciliar');
$routes->post('/inventario/concilarActivoConfirm', 'Inventary::SearchDataConciliarConfirm');
$routes->post('/inventario/conciliarFinish', 'Inventary::Conciliar');
$routes->get('/inventario/getProcessItems', 'Inventary::ProcessList');
$routes->get('/inventario/getInventaryItems', 'Inventary::SearchInventaryList');
$routes->get('/inventario/getActivoInfo/(:num)', 'Inventary::SearchActiveInfo/$1');
$routes->post('/inventario/getInventaryItemsFilter', 'Inventary::SearchInventaryListFilter');
$routes->post('/inventario/setFactura', 'Inventary::setFactura');
$routes->post('/inventario/setGarantia', 'Inventary::setGarantia');
$routes->post('/inventario/deleteNews', 'Inventary::deleteNews');

/** AJAX - downs **/
$routes->get('/bajas/getItems', 'Down::SearchList');
$routes->post('/bajas/getItemsFilter', 'Down::SearchListFilter');
$routes->post('/bajas/down', 'Down::activosDelete');


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
