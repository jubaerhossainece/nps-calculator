<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\UserController;
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
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/profile/show', [Admin\ProfileController::class, 'profile'])->name('admin.profile.show');
    Route::get('/change-password', [Admin\ProfileController::class, 'changePassword'])->name('admin.change-password');

    Route::get('/projects', [ProjectController::class, 'index'])->name('projects');
    Route::get('/projects/list', [ProjectController::class, 'list'])->name('projects.list');

    // routes for audiences
    Route::get('/audiences', [UserController::class, 'index'])->name('users');
    Route::get('/audiences/list/{type}', [UserController::class, 'list'])->name('users.list');
    Route::post('/audiences/{id}/status-change', [UserController::class, 'toggleStatus'])->name('users.status-change');
});
