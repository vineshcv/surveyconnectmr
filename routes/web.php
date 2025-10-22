<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\VendorRegistrationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::redirect('/', '/home');

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');


// Routes for super admin only
Route::middleware(['auth', 'role:super-admin'])->group(function () {
    
});

// Routes for any authenticated user (no role restriction)
Route::middleware(['auth'])->group(function () {
    Route::resource('clients', ClientController::class);
    Route::resource('vendors', VendorController::class);
    Route::resource('questions', QuestionController::class);
    Route::patch('questions/{question}/toggle-status', [QuestionController::class, 'toggleStatus'])->name('questions.toggleStatus');
    
});

Route::get('/vendor-registration', [VendorRegistrationController::class, 'index'])->name('vendor-registration.index');
Route::post('/vendor-registration', [VendorRegistrationController::class, 'store'])->name('vendor-registration.store');
Route::get('/vendor-registration/thankyou', function () {
    return view('vendors.thankyou');
})->name('vendor-registration.thankyou');

// pdf & csv & csv bulk upload
Route::get('/vendors/export/pdf', [VendorController::class, 'exportPdf'])->name('vendors.export.pdf');
Route::get('/vendors/export/csv', [VendorController::class, 'exportCsv'])->name('vendors.export.csv');
Route::post('/vendors/import/csv', [VendorController::class, 'importCsv'])->name('vendors.import.csv');

Route::get('/clients/export/pdf', [ClientController::class, 'exportPdf'])->name('clients.export.pdf');
Route::get('/clients/export/csv', [ClientController::class, 'exportCsv'])->name('clients.export.csv');

Route::get('/questions/export/pdf', [QuestionController::class, 'exportPdf'])->name('questions.export.pdf');
Route::get('/questions/export/csv', [QuestionController::class, 'exportCsv'])->name('questions.export.csv');
Route::resource('invoices', InvoiceController::class);

Route::resource('projects', ProjectController::class);
Route::post('/projects/{project}/clone', [ProjectController::class, 'cloneProject'])->name('projects.cloneProject');
Route::get('projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');

Route::post('/projects/{project}/assign-questions', [ProjectController::class, 'assignQuestions'])->name('projects.assignQuestions');
Route::delete('/projects/{project}/questions/{question}', [ProjectController::class, 'removeQuestion'])->name('projects.removeQuestion');

// Vendor management routes
Route::get('/projects/{project}/vendors', [ProjectController::class, 'manageVendors'])->name('projects.manageVendors');
Route::post('/projects/{project}/vendors/add-quota', [ProjectController::class, 'addQuotaToVendor'])->name('projects.addQuotaToVendor');
Route::put('/projects/{project}/vendors/{vendor}/quota', [ProjectController::class, 'updateVendorQuota'])->name('projects.updateVendorQuota');
Route::get('/projects/{project}/vendors/{vendor}/mapping', [ProjectController::class, 'editVendorMapping'])->name('projects.editVendorMapping');
Route::put('/projects/{project}/vendors/{vendor}/mapping', [ProjectController::class, 'updateVendorMapping'])->name('projects.updateVendorMapping');
Route::delete('/projects/{project}/vendors/{vendor}', [ProjectController::class, 'removeVendor'])->name('projects.removeVendor');

// Participants data route
Route::get('/projects/{project}/participants/data', [ProjectController::class, 'getParticipantsData'])->name('projects.getParticipantsData');

// Survey flow routes (replacing rdata functionality)
Route::get('/survey', [ProjectController::class, 'surveyRedirect'])->name('survey.redirect');
Route::get('/survey/questions/{hash}', [ProjectController::class, 'showQuestions'])->name('survey.questions');
Route::post('/survey/questions/{hash}', [ProjectController::class, 'submitQuestions'])->name('survey.submitQuestions');
Route::get('/final-redirect/{status}', [ProjectController::class, 'finalRedirect'])->name('final.redirect');
Route::get('/survey/complete', [ProjectController::class, 'surveyComplete'])->name('survey.complete');
Route::get('/survey/terminate', [ProjectController::class, 'surveyTerminate'])->name('survey.terminate');
Route::get('/survey/quotafull', [ProjectController::class, 'surveyQuotafull'])->name('survey.quotafull');
Route::get('/survey/securityfull', [ProjectController::class, 'surveySecurityFull'])->name('survey.securityFull');
Route::get('/survey/quota_complete', [ProjectController::class, 'quotaComplete'])->name('survey.quotaComplete');
Route::get('/survey/already_participate', [ProjectController::class, 'alreadyParticipate'])->name('survey.alreadyParticipate');
Route::get('/survey/project_pause', [ProjectController::class, 'projectPause'])->name('survey.projectPause');
Route::get('/survey/project_complete', [ProjectController::class, 'projectComplete'])->name('survey.projectComplete');
Route::get('/survey/urlerror', [ProjectController::class, 'urlError'])->name('survey.urlError');
Route::get('/survey/Iperror', [ProjectController::class, 'ipError'])->name('survey.ipError');





// pdf & csv & csv bulk upload
Route::resources([
    'roles' => RoleController::class,
    'users' => UserController::class,
    'products' => ProductController::class,
]);

