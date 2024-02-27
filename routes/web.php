<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\CustomAuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;

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

//Route::get('/', function (), {return view('index');});

// display the registration form
// Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');

// // handles user form submission
// Route::post('/register', [RegisterController::class, 'register'])->name('register'); 

//login and registration auth
Route::get('/', [CustomAuthController::class, 'login'])->name('login');
Route::post('/login-user', [CustomAuthController::class, 'loginUser'])->name('login-user');
Route::post('/logout', [CustomAuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () { 

    Route::get('/registration', [CustomAuthController::class, 'registration'])->name('register');
    Route::post('/register-user',[CustomAuthController::class, 'registerUser'])->name('register-user'); 

    //Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    //users routes
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/add', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        //domain routes
    Route::get('/domains', [DomainController::class, 'index'])->name('domains.index');
    Route::get('/domains/create', [DomainController::class, 'create'])->name('domains.create');
    Route::post('/domains', [DomainController::class, 'store'])->name('domains.store');
    Route::delete('/domains/{domain}', [DomainController::class, 'destroy'])->name('domains.destroy');
    Route::get('/domains/status', [DomainController::class, 'status'])->name('domains.status');
    Route::post('domains/update-expiry-dates', [DomainController::class, 'updateExpiryDates'])->name('domains.updateExpiryDates');
    //lookup api route
    Route::post('/lookup', [DomainController::class, 'lookup'])->name('lookup');

    //company routes
    Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
    Route::get('/companies/create', [CompanyController::class, 'create'])->name('companies.create');
    Route::post('/companies', [CompanyController::class, 'store'])->name('companies.store');
    Route::get('/companies/{company}/edit', [CompanyController::class, 'edit'])->name('companies.edit');
    Route::put('/companies/{company}', [CompanyController::class, 'update'])->name('companies.update');
    Route::delete('/companies/{company}', [CompanyController::class, 'destroy'])->name('companies.destroy');

// Routes that require authentication except for the login route
Route::get('/dashboard', [CustomAuthController::class, 'dashboard'])->name('dashboard')->middleware('auth');

 });

