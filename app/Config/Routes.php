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

/** Backoffice - Pagos **/
$routes->get('/pagos', 'Payment::Index');

/** Backoffice - Escaneo **/
$routes->get('/alta', 'Activo::Index');
$routes->get('/escaneo', 'Home::OnlyScan');

/** Backoffice - Inventario **/
$routes->get('/inventario', 'Inventary::Index');

/** Backoffice - Bajas **/
$routes->get('/bajas', 'Down::Index');

/** Backoffice - Carga excel **/
$routes->get('/carga', 'Activo::LoadActivos');

/** Backoffice - Empresas **/
$routes->get('/empresas', 'Company::Index');

/** AJAX - Carga Excel **/
$routes->get('/carga/ejemplo', 'Activo::DownloadExcelExample');
$routes->post('/carga/readExcel', 'Activo::ReadExcel');

/** AJAX - Inicio **/
$routes->get('/dashboard/data', 'Dashboard::getData');

/** AJAX - scanner **/
$routes->get('/activos/getFormData', 'Activo::GetDataForm');
$routes->post('/activos/search', 'Activo::SearchActivo');
$routes->post('/activos/validateNew', 'Activo::ValidateActivo');
$routes->post('/activos/new', 'Activo::NewActivo');
$routes->post('/activos/updateInfo', 'Activo::UpdateInfoActivo');
$routes->post('/activos/dinamicForm', 'Activo::UpdateSucursal');
$routes->post('/activos/setGeo', 'Activo::SetCoordenadas');
$routes->get('/activos/getImageFront/(:any)', 'Activo::GetImageFront/$1');
$routes->get('/activos/getImageLeft/(:any)', 'Activo::GetImageLeft/$1');
$routes->get('/activos/getImageRight/(:any)', 'Activo::GetImageRight/$1');
$routes->post('/activos/setImage', 'Activo::SetImage');
$routes->post('/activos/deleteImage', 'Activo::DeleteImage');
$routes->post('/activos/updateActivo', 'Activo::UpdateActivoFromDraft');
$routes->post('/activos/coordenadas', 'Activo::UpdateCoordenadas');

/** AJAX - inventary **/
$routes->get('/inventario/getFormData', 'Inventary::GetDataForm');
$routes->get('/inventario/getItems', 'Inventary::SearchItemList');
$routes->get('/inventario/getDraftInfo/(:any)', 'Inventary::SearchItemInfo/$1');
$routes->get('/inventario/getDraftDetails/(:any)', 'Inventary::SearcItemDetails/$1');
$routes->get('/inventario/getDraftBuyDetails/(:any)', 'Inventary::SearcItemBuyDetails/$1');
$routes->post('/inventario/saveDraftBuyDetails', 'Inventary::SaveDraftBuyDetails');
$routes->post('/inventario/draftToActivo', 'Inventary::draftToActivo');
$routes->post('/inventario/draftDelete', 'Inventary::draftDelete');
$routes->get('/inventario/concilar/(:any)', 'Inventary::SearchItemsConciliar/$1');
$routes->post('/inventario/concilarActivo', 'Inventary::SearchDataConciliar');
$routes->post('/inventario/concilarActivoConfirm', 'Inventary::SearchDataConciliarConfirm');
$routes->post('/inventario/conciliarFinish', 'Inventary::Conciliar');
$routes->get('/inventario/getProcessItems', 'Inventary::ProcessList');
$routes->get('/inventario/getInventaryItems', 'Inventary::SearchInventaryList');
$routes->get('/inventario/getActivoInfo/(:any)', 'Inventary::SearchActiveInfo/$1');
$routes->post('/inventario/getInventaryItemsFilter', 'Inventary::SearchInventaryListFilter');
$routes->post('/inventario/sucursales', 'Inventary::UpdateSucursal');
$routes->post('/inventario/setFactura', 'Inventary::setFactura');
$routes->post('/inventario/setGarantia', 'Inventary::setGarantia');
$routes->post('/inventario/deleteNews', 'Inventary::deleteNews');

$routes->get('/excel/activos', 'Activo::ExcelActivos');
$routes->get('/activo/photos/(:alphanum)/(:num)', 'Activo::ActivoImage/$1/$2');
$routes->get('/excel/draft', 'Activo::ExcelDraft');
$routes->get('/activos/photos/(:alphanum)/(:num)', 'Activo::DraftImage/$1/$2');

/** AJAX - downs **/
$routes->get('/bajas/getItems', 'Down::SearchList');
$routes->post('/bajas/getItemsFilter', 'Down::SearchListFilter');
$routes->post('/bajas/down', 'Down::activosDelete');

/**  Usuarios **/
$routes->get('/usuarios', 'User::Users');
$routes->get('/usuarios/ccs', 'User::GetCCs');
$routes->get('/usuarios/data', 'User::getUserData');
$routes->post('/usuarios/generateurl', 'User::GenerateUrl');
$routes->post('/usuarios/sendEmail', 'User::SendEmail');
$routes->post('/usuarios/usuario', 'User::GetOneUser');
$routes->post('/usuarios/actualizar', 'User::Update');
$routes->post('/usuarios/delete', 'User::Delete');
$routes->post('/usuarios/getcc', 'User::GetMyCC');

$routes->get('/usuarios/carga', 'User::InitialExcel');
$routes->get('/usuarios/ejemplo', 'User::DownloadExcelExample');
$routes->post('/usuarios/carga', 'User::ReadExcel');

$routes->get('/carga/(:any)', 'Home::Url/$1');

/** AJAX - Empresa **/
$routes->post('/empresas/newCompany', 'Company::NewCompany');
$routes->post('/empresas/changeCompany', 'Company::ChangeCompany');
$routes->post('/empresas/update', 'Company::UpdateCompany');
$routes->post('/empresas/setLogo', 'Company::ChangeImage');
$routes->post('/empresas/finishPeriod', 'Company::FinishPeriod');
$routes->post('/empresas/newSucursal', 'Company::NewSucursal');
$routes->post('/empresas/editSucursal', 'Company::EditSucursal');
$routes->post('/empresas/deleteSucursal', 'Company::DeleteSucursal');
$routes->post('/empresas/newArea', 'Company::NewArea');
$routes->post('/empresas/editArea', 'Company::EditArea');
$routes->post('/empresas/deleteArea', 'Company::DeleteArea');
$routes->post('/empresas/newTipo', 'Company::NewTipo');
$routes->post('/empresas/editTipo', 'Company::EditTipo');
$routes->post('/empresas/deleteTipo', 'Company::DeleteTipo');
$routes->post('/empresas/newCC', 'Company::NewCC');
$routes->post('/empresas/editCC', 'Company::EditCC');
$routes->post('/empresas/deleteCC', 'Company::DeleteCC');

/** AJAX - Dashboard **/
$routes->post('/dashboard/map', 'Dashboard::getActivosMap');
$routes->post('/dashboard/filter', 'Dashboard::getDataFilter');

//details
$routes->get('/macal/inv', 'Inventary::MacalInvDetails');

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
