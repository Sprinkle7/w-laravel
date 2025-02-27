<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CompanyController;
use Illuminate\Support\Facades\Auth;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/companies', function () {
        return view('companies');
    })->name('companies');
    
    Route::post('/companies/import', [CompanyController::class, 'import'])->name('companies.import');
    Route::post('/companies/import-csv', [CompanyController::class, 'import_frontend'])->name('companies.import_frontend');
    Route::delete('/companies/{id}', [CompanyController::class, 'destroy'])->name('companies.destroy');
    Route::put('/companies/{id}', [CompanyController::class, 'update'])->name('companies.update');
    Route::resource('users', UserController::class)->except(['show']);
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::post('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::post('/companies', [CompanyController::class, 'store'])->name('companies.store');
    Route::get('/get_companies', [CompanyController::class, 'index'])->name('companies.index');
    Route::get('/companies/{id}/edit', [CompanyController::class, 'edit'])->name('companies.edit');
    Route::delete('/companies/bulk-delete', [CompanyController::class, 'bulkDelete'])->name('companies.bulk-delete');
});


Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login'); // Redirect to login after logout
})->name('logout')->middleware('auth');

require __DIR__.'/auth.php';
