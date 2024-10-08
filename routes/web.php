<?php

use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Login\UserController;
use App\Http\Middleware\Isadmin;
use App\Http\Middleware\Ismember;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Auth;
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

Route::get('/', function () {
    return view('client.index');
})->name('index');

Route::get('/shop', function () {
    return view('client.shop');
})->name('shop');


Route::get('/about', function () {
    return view('client.about');
})->name('about');


Route::get('/blog', function () {
    return view('client.blog');
})->name('blog');



Route::get('/cart', function () {
    return view('client.cart');
})->name('cart');



Route::get('/checkout', function () {
    return view('client.checkout');
})->name('checkout');



Route::get('/contact', function () {
    return view('client.contact');
})->name('contact');




Route::get('/services', function () {
    return view('client.services');
})->name('services');



Route::get('/thankyou', function () {
    return view('client.thankyou');
})->name('thankyou');







//Login
Route::get('login', [UserController::class, 'showform'])->name('login');
Route::post('loginpost', [UserController::class, 'login'])->name('loginpost');
Route::post('registerpost', [UserController::class, 'register'])->name('registerpost');
Route::post('logout', [UserController::class, 'logout'])->name('logout');
// Hiển thị form quên mật khẩu
Route::get('forgot-password', [UserController::class,'formsendmail'])->middleware('guest')->name('password.request');

// Xử lý yêu cầu gửi email đặt lại mật khẩu
Route::post('forgot-password', [UserController::class, 'sendResetLinkEmail'])->middleware('guest')->name('password.email');
// Hiển thị form đặt lại mật khẩu
Route::get('reset-password/{token}', function ($token) {
    return view('login.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');

// Xử lý việc đặt lại mật khẩu
Route::post('reset-password', [UserController::class, 'reset'])->middleware('guest')->name('password.update');




// admin
/**
 * 'auth' : check nếu như chưa đăng nhập mà cố tình truy cập vào trang dashboard thì sẽ điều hướng đến login
 * 'isadmin' : check nếu như bạn là type admin thì bạn có thể truy cập vào dashboard còn là member thì sẽ báo lỗi 403
 */
Route::prefix('dashboard')->middleware(['auth', 'isadmin'])->group(function () {
    Route::get('/',         [AdminUserController::class, 'index'])->name('dashboard');

    Route::get('/account',  [AdminUserController::class, 'list'])->name('dashboard.account');

    Route::get('/account/restore/{id}', [AdminUserController::class, 'restore'])->name('account.restore');

    Route::get('/account/destroy/{id}',     [AdminUserController::class, 'destroy'])->name('account.destroy');

    Route::get('/account/setrole/{id}', [AdminUserController::class, 'setrole'])->name('account.setrole');

    Route::get('/account/downgrade/{id}', [AdminUserController::class, 'downgrade'])->name('account.downgrade');

    Route::get('/account/delete/{id}',     [AdminUserController::class, 'delete'])->name('account.delete');

    Route::resource('products', ProductController::class);
    
});
