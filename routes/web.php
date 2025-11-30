<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/**
 * Admin Panel Routes
 * Only accessible by users with is_admin = true
 */
// Default admin route - redirect to dashboard
Route::redirect('/admin', '/admin/dashboard');

Route::prefix('admin')->middleware(['admin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminUserController::class, 'dashboard'])->name('admin.dashboard');
    
    // User Management
    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/{id}', [AdminUserController::class, 'show'])->name('admin.users.show');
    
    // Companies Management
    Route::resource('companies', App\Http\Controllers\Admin\CompanyController::class, [
        'as' => 'admin'
    ]);
    
    // Plans Management
    Route::resource('plans', App\Http\Controllers\Admin\PlanController::class, [
        'as' => 'admin'
    ]);
    
    // Questionnaires Management
    Route::resource('questionnaires', App\Http\Controllers\Admin\QuestionnaireController::class, [
        'as' => 'admin'
    ]);
    
    // Questions Management (nested under questionnaires)
    Route::resource('questionnaires.questions', App\Http\Controllers\Admin\QuestionController::class, [
        'as' => 'admin',
        'except' => ['index'] // Questions are listed within questionnaire show page
    ]);
    
    // Question Options Management (nested under questions)
    Route::resource('questions.options', App\Http\Controllers\Admin\QuestionOptionController::class, [
        'as' => 'admin',
        'except' => ['index', 'show'] // Options are managed within question edit page
    ]);
    
    // Callback Requests Management
    Route::resource('callback-requests', App\Http\Controllers\Admin\CallbackRequestController::class, [
        'as' => 'admin'
    ]);
    Route::post('callback-requests/{callbackRequest}/update-status', [App\Http\Controllers\Admin\CallbackRequestController::class, 'updateStatus'])->name('admin.callback-requests.update-status');
    
    // Ads & Promotions Management
    Route::resource('ads', App\Http\Controllers\Admin\AdController::class, [
        'as' => 'admin'
    ]);
    Route::patch('ads/{ad}/toggle-status', [App\Http\Controllers\Admin\AdController::class, 'toggleStatus'])->name('admin.ads.toggleStatus');
    Route::post('ads/bulk-action', [App\Http\Controllers\Admin\AdController::class, 'bulkAction'])->name('admin.ads.bulk');
    
    // Activity Logs (Read-only)
    Route::get('activity-logs', [App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->name('admin.activity-logs.index');
    Route::get('activity-logs/{id}', [App\Http\Controllers\Admin\ActivityLogController::class, 'show'])->name('admin.activity-logs.show');
    Route::get('activity-logs-export', [App\Http\Controllers\Admin\ActivityLogController::class, 'export'])->name('admin.activity-logs.export');
    
    // Settings
    Route::get('settings', function () {
        return view('admin.settings.index');
    })->name('admin.settings.index');
});

require __DIR__.'/auth.php';
