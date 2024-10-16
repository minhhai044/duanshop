<?php

use App\Http\Controllers\Admin\CapacityController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\GeneralController;
use App\Http\Controllers\Client\OrderController;
use App\Http\Controllers\Login\UserController;
use App\Http\Middleware\CheckAddProductCart;
use App\Http\Middleware\CheckQuantityCheckOutCart;
use App\Http\Middleware\CheckQuantityProductCart;
use App\Http\Middleware\CheckQuantityUpdateCart;
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




Route::prefix('/')->group(function () {
    Route::get('/', [GeneralController::class, 'index'])->name('index');
    Route::get('shop', [GeneralController::class, 'shop'])->name('shop');

    Route::get('{detail}/detail', [GeneralController::class, 'detail'])->name('detail');








    Route::post('addcart', [CartController::class, 'addcart'])->name('addcart')->middleware([ 'auth',CheckQuantityProductCart::class,CheckAddProductCart::class]);
    Route::get('listcart', [CartController::class, 'listcart'])->name('listcart')->middleware('auth');
    Route::delete('{cartitem}/cartitemdelete', [CartController::class, 'cartItemDelete'])->name('cart.delete');
    Route::delete('{cartitem}/cartitemdeleteall',[CartController::class,'cartitemdeleteall'])->name('cart.delete.all');
    Route::get('checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('checkout', [OrderController::class, 'storeCheckout'])->name('store.checkout')->middleware(CheckQuantityCheckOutCart::class);






    Route::get('about', [GeneralController::class, 'about'])->name('about');
    Route::get('blog', [GeneralController::class, 'blog'])->name('blog');


    Route::get('contact', [GeneralController::class, 'contact'])->name('contact');
    Route::get('services', [GeneralController::class, 'services'])->name('services');
    Route::get('thankyou', [GeneralController::class, 'thankyou'])->name('thankyou');
});



//Login
Route::get('login', [UserController::class, 'showform'])->name('login');
Route::post('loginpost', [UserController::class, 'login'])->name('loginpost');
Route::post('registerpost', [UserController::class, 'register'])->name('registerpost');
Route::post('logout', [UserController::class, 'logout'])->name('logout');
// Hiển thị form quên mật khẩu
Route::get('forgot-password', [UserController::class, 'formsendmail'])->middleware('guest')->name('password.request');

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

    Route::resource('capacities', CapacityController::class);

    Route::resource('categories', CategoryController::class);

    Route::resource('colors', ColorController::class);
});
