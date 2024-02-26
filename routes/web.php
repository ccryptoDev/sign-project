<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SignController;
use App\Http\Controllers\FontController;
use App\Http\Controllers\ManageUserController;
use App\Http\Controllers\SocketController;

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
    // return view('welcome');
});

Route::get('/login', [AuthController::class, 'login_view'])->name('login');
Route::post('login', [AuthController::class, 'login_request']);
Route::get('/register', [AuthController::class, 'register_view'])->name('register');
Route::post('register', [AuthController::class, 'register_request']);
Route::get('/forgot-password', [AuthController::class, 'forgot_password'])->name('forgot');
Route::post('forgot-password', [AuthController::class, 'forgot_password_request'])->name('forgot-password');
Route::get('/reset-password/{token}', function ($token) {
    return view('auth.reset-password', ['token' => $token]);
})->name('password.reset');
Route::post('reset-password', [AuthController::class, 'reset_password'])->name('reset-password');

Route::get('logout', [AuthController::class, 'logout'])->name('logout');

// Webserver Control
Route::post('change-brightness', [SocketController::class, 'change_brightness']);
Route::post('get-brightness', [SocketController::class, 'get_brightness']);
Route::post('send_img_playlist', [SocketController::class, 'send_img_playlist']);
Route::get('send-image', [SocketController::class, 'compress_image']);
Route::post('send-image', [SocketController::class, 'compress_image']);
Route::post('get-currentNumber', [SocketController::class, 'get_current_number']);
Route::post('send-specNumber', [SocketController::class, 'send_picture_specific_screen']);
Route::post('read-information', [SocketController::class, 'read_information']);
Route::post('swith-mode', [SocketController::class, 'swith_mode']);
Route::post('get-current-playlist', [SocketController::class, 'get_current_playlist']);

Route::get('tom-test', [SocketController::class, 'TomTest']);

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    // Font Editor
    Route::get('create-font', [FontController::class, 'view_font_editor'])->name('create-font');
    Route::post('save-font', [FontController::class, 'save_font']);
    Route::get('font-lists', [FontController::class, 'font_lists']);
    Route::post('upload-font', [FontController::class, 'upload_font']);
    // Sign Editor
    Route::get('sign-editor', [SignController::class, 'sign_editor'])->name('sign-editor');
    // Profile
    Route::get('/profile', [UserController::class, 'view_profile'])->name('profile');
    Route::post('profile', [UserController::class, 'update_profile'])->name('update-profile');
    // Change Password
    Route::get('change-password', [UserController::class, 'change_password'])->name('change-password');
    Route::post('update-password', [UserController::class, 'update_password'])->name('update-password');
    // Manage Users
    Route::get('manage-users', [ManageUserController::class, 'view_management'])->name('user-management');
    Route::post('list-management', [ManageUserController::class, 'list_users']);
    Route::post('new-user', [ManageUserController::class, 'create_new_user']);
    Route::post('update-user', [ManageUserController::class, 'update_user']);
    Route::get('delete-user/{id}', [ManageUserController::class, 'delete_user']);

    // Socket Connection
});
