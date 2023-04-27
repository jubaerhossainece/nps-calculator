<?php

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

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard.index');
});
