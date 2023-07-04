<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index', ['filter' => 'Auth']);

$routes->get('/login',              'Auth::index', ['filter' => 'NoAuth']);
$routes->post('/login',             'Auth::login', ['filter' => 'NoAuth']);
$routes->get('/register',           'Auth::register', ['filter' => 'NoAuth']);
$routes->post('/register',          'Auth::attemptRegister', ['filter' => 'NoAuth']);

$routes->get('/logout', 'Auth::logout');


$routes->get('kuesioner/', 'Home::kuesioner', ['filter' => 'Auth']);
$routes->post('kuesioner/reload-pertanyaan', 'Home::reloadPertanyaan');
$routes->post('kuesioner/save-jawaban/(:num)', 'Home::saveJawaban/$1');


$routes->get('evaluasi/', 'Home::evaluasi', ['filter' => 'Auth']);

$routes->get('/user'                                , 'User::index');
$routes->post('/user/modal-import'                  , 'User::modalImport');
$routes->post('/user/import'                        , 'User::import');
$routes->post('/user/reload-datatables'             , 'User::reloadDatatables');


$routes->get('/jawaban'                                 , 'Jawaban::index');
$routes->get('/jawaban/export-excel'                    , 'Jawaban::exportExcel');
$routes->get('/jawaban/(:num)'                          , 'Jawaban::detail/$1');
$routes->delete('/jawaban/(:num)/delete'                , 'Jawaban::delete/$1');
$routes->post('/jawaban/(:num)/reload-pertanyaan'       , 'Jawaban::reloadPertanyaan/$1');
$routes->post('/jawaban/(:num)/save-jawaban/(:num)'     , 'Jawaban::saveJawaban/$1/$2');
$routes->post('/jawaban/reload-datatables'              , 'Jawaban::reloadDatatables');
$routes->post('/jawaban/lock'                           , 'Jawaban::lock');

$routes->get('/pertanyaan'                              , 'Pertanyaan::index');
$routes->post('/pertanyaan/modal-tambah'                , 'Pertanyaan::modalTambah');
$routes->post('/pertanyaan/save'                        , 'Pertanyaan::save', ['filter' => 'Auth']);
$routes->post('/pertanyaan/reload-datatables'           , 'Pertanyaan::reloadDatatables');
$routes->post('/pertanyaan/get-answer-choice'            , 'Pertanyaan::getAnswerChoice');
$routes->post('/pertanyaan/delete/(:num)'               , 'Pertanyaan::delete/$1');
$routes->post('/pertanyaan/(:num)/change-number'         , 'Pertanyaan::changeNumber/$1');

// $routes->get('/user', 'User::index', ['filter' => 'Auth']);
// $routes->post('/user/modal-import', 'User::modalImport', ['filter' => 'Auth']);
// $routes->post('/user/import', 'User::import', ['filter' => 'Auth']);
// $routes->post('/user/reload-datatables', 'User::reloadDatatables', ['filter' => 'Auth']);


// $routes->get('/pertanyaan'                              , 'Pertanyaan::index', ['filter' => 'Auth']);
// $routes->post('/pertanyaan/modal-tambah'                , 'Pertanyaan::modalTambah', ['filter' => 'Auth']);
// $routes->post('/pertanyaan/save'                        , 'Pertanyaan::save', ['filter' => 'Auth']);
// $routes->post('/pertanyaan/reload-datatables'           , 'Pertanyaan::reloadDatatables', ['filter' => 'Auth']);
// $routes->post('/pertanyaan/get-answer-choice'            , 'Pertanyaan::getAnswerChoice', ['filter' => 'Auth']);
// $routes->post('/pertanyaan/(:num)/change-number'         , 'Pertanyaan::changeNumber/$1', ['filter' => 'Auth']);
/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
