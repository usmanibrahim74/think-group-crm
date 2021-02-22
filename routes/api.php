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
use App\Http\Controllers\Api\EmployerController;
use App\Http\Controllers\Api\DashboardController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

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


    Route::get('industries', [CandidateController::class, 'getIndustries']);
    Route::get('industries/all', [CandidateController::class, 'getAllIndustries']);
    Route::post('industries', [CandidateController::class, 'addIndustry']);
    Route::put('industries/{id}', [CandidateController::class, 'updateIndustry']);
    Route::delete('industries/{id}', [CandidateController::class, 'deleteIndustry']);

    Route::get('candidates', [CandidateController::class, 'getCandidates']);
    Route::get('candidates/shortlisted', [CandidateController::class, 'getShortlistedCandidates']);
    Route::post('candidates/{id}/comments', [CandidateController::class, 'addComment']);
    Route::get('candidates/{id}/shortlistedby', [CandidateController::class, 'getShortlistedBy']);
    Route::put('candidates/{id}/shortlist', [CandidateController::class, 'shortlistCandidate']);
    Route::post('candidates', [CandidateController::class, 'addCandidate']);
    Route::get('candidates/{id}', [CandidateController::class, 'getCandidate']);
    Route::put('candidates/{id}', [CandidateController::class, 'updateCandidate']);
    Route::delete('candidates/{id}', [CandidateController::class, 'deleteCandidate']);

    Route::delete('uploads/{upload_id}', [CandidateController::class, 'deleteUpload']);

    Route::get('employers/profiles', [EmployerController::class, 'getProfiles']);
    Route::get('employers/profiles/all', [EmployerController::class, 'getAllProfiles']);
    Route::post('employers/profiles', [EmployerController::class, 'addProfile']);
    Route::get('employers/profiles/{id}/shortlisted', [EmployerController::class, 'getShortlisted']);
    Route::get('employers/profiles/{id}', [EmployerController::class, 'getProfile']);
    Route::put('employers/profiles/{id}', [EmployerController::class, 'updateProfile']);
    Route::delete('employers/profiles/{id}', [EmployerController::class, 'deleteProfile']);

    Route::put('employers/profiles/{id}/candidates', [EmployerController::class, 'syncCandidates']);



    Route::get('employers/accounts', [EmployerController::class, 'getAccounts']);
    Route::post('employers/accounts', [EmployerController::class, 'addAccount']);
    Route::delete('employers/accounts/{id}', [EmployerController::class, 'deleteAccount']);
    Route::get('employers/accounts/{id}', [EmployerController::class, 'getAccount']);
    Route::put('employers/accounts/{id}', [EmployerController::class, 'updateAccount']);
    Route::put('employers/accounts/{id}/change-password', [EmployerController::class, 'changeAccountPassword']);

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
