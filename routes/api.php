<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OAuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ManagementController;
use App\Http\Controllers\Api\CandidateController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\DepartmentController;
use Illuminate\Support\Facades\Log;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is wapihere you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/webhook',function (){
    Log::info('hanji');
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('logout', [LoginController::class, 'logout']);

    Route::get('user', [UserController::class, 'current']);
    Route::get('abilities', [UserController::class, 'abilities']);

    Route::patch('settings/profile', [ProfileController::class, 'update']);
    Route::patch('settings/password', [PasswordController::class, 'update']);

    Route::get('/dashboard', [DashboardController::class,'get']);

    /* users routes */
    Route::get('users', [ManagementController::class, 'getUsers']);
    Route::get('users/{user_id}', [ManagementController::class, 'getUser']);
    Route::put('users/{user_id}/password', [ManagementController::class, 'changeUserPassword']);
    Route::post('users/add', [ManagementController::class, 'addUser']);
    Route::put('users/{user_id}', [ManagementController::class, 'updateUser']);
    Route::delete('users/{user_id}', [ManagementController::class, 'deleteUser']);

    /* roles routes */
    Route::get('roles', [ManagementController::class, 'getRoles']);
    Route::get('roles/all', [ManagementController::class, 'getAllRoles']);
    Route::get('roles/{role_id}', [ManagementController::class, 'getRole']);
    Route::post('roles/add', [ManagementController::class, 'addRole']);
    Route::put('roles/{role_id}', [ManagementController::class, 'updateRole']);
    Route::delete('roles/{role_id}', [ManagementController::class, 'deleteRole']);

    /* permissions routes */
    Route::get('permissions', [ManagementController::class, 'getPermissions']);
    Route::get('permissions/all', [ManagementController::class, 'getAllPermissions']);
    Route::get('permissions/{permission_id}', [ManagementController::class, 'getPermission']);
    Route::post('permissions/add', [ManagementController::class, 'addPermission']);
    Route::put('permissions/{permission_id}', [ManagementController::class, 'updatePermission']);
    Route::delete('permissions/{permission_id}', [ManagementController::class, 'deletePermission']);

    Route::get('departments', [DepartmentController::class, 'getDepartments']);
//    Route::get('responsibilities', [DepartmentController::class, 'getResponsibilities']);

    Route::get('industries', [CandidateController::class, 'getIndustries']);
    Route::get('industries/all', [CandidateController::class, 'getAllIndustries']);
    Route::post('industries', [CandidateController::class, 'addIndustry']);
    Route::put('industries/{id}', [CandidateController::class, 'updateIndustry']);
    Route::delete('industries/{id}', [CandidateController::class, 'deleteIndustry']);

    Route::get('candidates', [CandidateController::class, 'getCandidates']);
    Route::get('candidates/shortlisted', [CandidateController::class, 'getShortlistedCandidates']);
    Route::post('candidates/{id}/comments', [CandidateController::class, 'addComment']);
    Route::post('candidates/shortlist/{id}/comments', [CandidateController::class, 'addShortlistComment']);
    Route::get('candidates/{id}/shortlistedby', [CandidateController::class, 'getShortlistedBy']);
    Route::put('candidates/{id}/shortlist', [CandidateController::class, 'shortlistCandidate']);
    Route::post('candidates', [CandidateController::class, 'addCandidate']);
    Route::get('candidates/{id}', [CandidateController::class, 'getCandidate']);
    Route::put('candidates/{id}', [CandidateController::class, 'updateCandidate']);
    Route::delete('candidates/{id}', [CandidateController::class, 'deleteCandidate']);

    Route::delete('uploads/{upload_id}', [CandidateController::class, 'deleteUpload']);

    Route::get('clients/profiles', [ClientController::class, 'getProfiles']);
    Route::get('clients/profiles/all', [ClientController::class, 'getAllProfiles']);
    Route::post('clients/profiles', [ClientController::class, 'addProfile']);
    Route::get('clients/profiles/{id}/shortlisted', [ClientController::class, 'getShortlisted']);
    Route::get('clients/profiles/{id}', [ClientController::class, 'getProfile']);
    Route::put('clients/profiles/{id}', [ClientController::class, 'updateProfile']);
    Route::delete('clients/profiles/{id}', [ClientController::class, 'deleteProfile']);

    Route::put('clients/profiles/{id}/candidates', [ClientController::class, 'syncCandidates']);



    Route::get('clients/accounts', [ClientController::class, 'getAccounts']);
    Route::post('clients/accounts', [ClientController::class, 'addAccount']);
    Route::delete('clients/accounts/{id}', [ClientController::class, 'deleteAccount']);
    Route::get('clients/accounts/{id}', [ClientController::class, 'getAccount']);
    Route::put('clients/accounts/{id}', [ClientController::class, 'updateAccount']);
    Route::put('clients/accounts/{id}/change-password', [ClientController::class, 'changeAccountPassword']);

});

Route::group(['middleware' => 'guest:api'], function () {
    Route::post('login', [LoginController::class, 'login']);
    Route::post('register', [RegisterController::class, 'register']);

    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
    Route::post('password/reset', [ResetPasswordController::class, 'reset']);

    Route::post('email/verify/{user}', [VerificationController::class, 'verify'])->name('verification.verify');
    Route::post('email/resend', [VerificationController::class, 'resend']);

    Route::post('oauth/{driver}', [OAuthController::class, 'redirect']);
    Route::get('oauth/{driver}/callback', [OAuthController::class, 'handleCallback'])->name('oauth.callback');
});
