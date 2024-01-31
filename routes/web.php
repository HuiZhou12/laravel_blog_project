<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\UploadController;

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


// Route::get('',function(){
//     return view('welcome');
// });
//blog
Route::get('/', function () {
    //用户访问根目录 / 时，会被重定向到 /blog
    return redirect('/admin');
});
Route::get('blog', [BlogController::class, 'index'])->name('blog.home');
Route::get('blog/{slug}', [BlogController::class, 'showPost'])->name('blog.detail');


//后台路由
Route::get('/admin', function(){
    return redirect('/admin/post');
});
//在 Laravel 中，auth 中间件用于验证用户是否已经登录。该中间件在路由中被应用时，会检查用户的身份认证状态，如果用户已登录，则请求将继续通过路由；如果用户未登录，则会被重定向到登录页面。
Route::middleware('auth')->group(function () {
/*   
*这段代码是 Laravel 路由的一个快捷方式，用于定义处理资源路由的各个操作（例如，index, create, 
*store, show, edit, update, destroy）的路由规则。在这里，Route::resource 定义了 admin/post 
*的资源路由，并使用 except 方法指定不需要包含的操作，即不需要包含 create 和 edit 操作的路由规则。
*/
    Route::resource('admin/post', PostController::class)->except('show');
    Route::resource('admin/tag', TagController::class)->except('show');
    Route::get('admin/upload', [UploadController::class, 'index']); 
        Route::post('admin/upload/file', [UploadController::class, 'uploadFile']);
        Route::delete('admin/upload/file', [UploadController::class, 'deleteFile']);
        Route::post('admin/upload/folder', [UploadController::class, 'createFolder']);
        Route::delete('admin/upload/folder', [UploadController::class, 'deleteFolder']);
});
//登录退出
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');