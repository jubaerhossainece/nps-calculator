<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\ReportAbuseController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\twoFaVerificationController;
use App\Models\ReportAbuseForProjectLink;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;


Route::get(
    '/clear-cache',
    function () {
        Artisan::call('config:cache');
        Artisan::call('cache:clear');
        Artisan::call('optimize');
        Artisan::call('view:clear');
        Artisan::call('route:cache');
        return '<h1>Cache facade value cleared</h1>';
        // return vie')->with('<h1>Cache facade value cleared</h1>');
    }
);

Route::get('/sym-link', function(){
    Artisan::call('storage:link');
    return response([
        'status' => true,
        'message' => 'Symbolic link created.'
    ]);
});

require __DIR__ . '/auth.php';



// Route::get('login/{provider}', [Api\SocialAuthController::class, 'redirectToProvider']);
// Route::get('login/{provider}/callback', [Api\SocialAuthController::class, 'handleProviderCallback']);

Route::group(['middleware' => 'auth'], function () {

    Route::group(['middleware' => '2Fa'], function () {
    // Dashboard routes
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard/recent-user', [Admin\DashboardController::class, 'recentUser']);
    Route::get('/dashboard/user/{type}/chart', [Admin\DashboardController::class, 'userChartData']);
    Route::get('/dashboard/project-feedback/chart', [Admin\DashboardController::class, 'projectFeedbackChartData']);
    Route::get('/dashboard/nps-score/chart', [Admin\DashboardController::class, 'npsScoreChartData']);
    
    // Profile 
    Route::get('/profile/show', [Admin\ProfileController::class, 'show'])->name('admin.profile.show');
    Route::get('/profile/edit', [Admin\ProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::put('/profile/update', [Admin\ProfileController::class, 'update'])->name('admin.profile.update');
    
    Route::get('/password/edit', [Admin\ProfileController::class, 'editPassword'])->name('admin.password.edit');
    Route::put('/password/update', [Admin\ProfileController::class, 'updatePassword'])->name('admin.password.update');

    // Project
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects');
    Route::get('/projects/list', [ProjectController::class, 'list'])->name('projects.list');
    Route::post('/project-link/{id}/status-change', [ProjectController::class, 'toggleStatus'])->name('project-link.status-change');


    // Routes for users
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/users/list/{type}', [UserController::class, 'list'])->name('users.list');
    Route::post('/users/{id}/status-change', [UserController::class, 'toggleStatus'])->name('users.status-change');

    //2fa setting opetions
    Route::get('/2fa-setting', [twoFaVerificationController::class, 'index'])->name('twoFa.index');
    Route::get('/2fa-status-modify', [twoFaVerificationController::class, 'twoFaEnableStatus'])->name('twoFa.status-change');

    //Report abuse 
    Route::get('/abuse-reports', [ReportAbuseController::class, 'index'])->name('abuse-reports');
    Route::get('/show-reports/all/{project_link_id}', [ReportAbuseController::class, 'showAllReportRecords'])->name('abuse-reports-records-show');
    Route::get('/abuse-reports-records/all/{project_link_id}', [ReportAbuseController::class, 'getReportRecords'])->name('abuse-reports-records');
    Route::get('/abuse-reports-records/top-five/{project_link_id}', [ReportAbuseController::class, 'getReportRecordsTopFive'])->name('abuse-reports-records-top-five');
    Route::get('/abuse-reports/list/{type}', [ReportAbuseController::class, 'list'])->name('abuse-reports.list');
    });
});
