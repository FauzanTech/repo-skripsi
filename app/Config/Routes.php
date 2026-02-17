<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\MasterData;
use App\Controllers\UserReference;
use App\Controllers\User;
use App\Controllers\Notification;
use App\Controllers\ReferenceManagment;

/**
 * @var RouteCollection $routes
 */
$routes->get('/test', 'Home::test');
// $routes->get('/', 'Home::index');
$routes->get('/', 'Home::referenceList');
$routes->get('/list', 'Home::referenceList');
$routes->get('/list-item', 'Home::getListItem');
$routes->get('/search-embed', 'Home::embedSearch');
$routes->get('/published-reference', 'ReferenceManagment::index');
$routes->get('/unpublished-reference', 'ReferenceManagment::unpublishedReference');
$routes->get('/rejected-reference', 'ReferenceManagment::rejectedReference');
$routes->get('/reference-status', 'ReferenceManagment::referenceDetail');
$routes->post('/validate-document', 'ReferenceManagment::validateDocument');
$routes->post('/update-reference-status', 'ReferenceManagment::updateReferenceStatus');
$routes->post('/check-document-validation', 'ReferenceManagment::checkDocumentValidation');
$routes->get('/show-statement-letter/(:any)', 'ReferenceManagment::showStatementLetter/$1');
$routes->get('/show-reference-file/(:any)', 'ReferenceManagment::showReferenceFile/$1');
$routes->get('/dashboard', 'ReferenceManagment::dashboard');

$routes->get('/prodi', 'MasterData::prodi');
$routes->post('/insert-new-prodi', 'MasterData::insertNewProdi');
$routes->get('/reference-type', 'MasterData::referenceType');
$routes->get('/diciplines', 'MasterData::disciplines');
$routes->post('/insert-diciplines', 'MasterData::insertDisciplines');
$routes->get('/my-reference', 'UserReference::myReference');
$routes->get('/mahasiswa', 'MasterData::mahasiswa');
$routes->get('/dosen', 'MasterData::dosen');
$routes->post('/delete-member', 'MasterData::deleteMember');
$routes->post('/insert-new-member', 'MasterData::insertMember');
$routes->get('/admin', 'MasterData::admin');
$routes->post('/insert-new-admin', 'MasterData::insertAdmin');
// $routes->post('/new-member', 'MasterData::insertNewMember');
$routes->get('/auth', 'AuthController::login');
$routes->get('/logout', 'AuthController::logout');
$routes->get('/new-reference', 'UserReference::index', ['as' => 'new-reference']);
$routes->get('/download-reference/(:any)', 'UserReference::downloadReferenceFile/$1');
$routes->get('/StatementFile', 'UserReference::downloadFile');
$routes->get('/reference-detail', 'Home::referenceDetail');
$routes->get('/edit-reference/(:any)', 'UserReference::editReference/$1');
$routes->get('/get-author-name', 'UserReference::findAuthorName');

$routes->post('/create-reference-form', 'UserReference::createReferenceForm');
$routes->get('/new-reference-form', 'UserReference::newReferenceForm');
$routes->post('/insert-new-reference', 'UserReference::InsertNewReference');
$routes->post('/import-member', 'MasterData::importMember');
$routes->post('/insert-batch-member', 'MasterData::insertBatchMember');
$routes->post('/auth-proces', 'AuthController::loginProcess');

$routes->get('/register', 'AuthController::register');
$routes->post('/register-proces', 'AuthController::registerProcess');
$routes->get('/change-password', 'AuthController::changePasswordView');
$routes->post('/change-password-proces', 'AuthController::changePassword');

$routes->get('/get-member', 'UserReference::getMember');

// notification
$routes->get('/alert', 'Notification::index');