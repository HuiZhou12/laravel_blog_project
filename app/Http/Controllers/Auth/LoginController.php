<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    public function showLoginForm()
    {
        return view('admin.auth.login');
    }
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
//当使用 Auth::attempt($credentials) 方法时，Laravel 
//会自动在数据库中查找与提供的认证凭据匹配的用户记录。这个方法
//期望 $credentials 是一个关联数组，其中包含用户的认证信息，通常包括用户名（如 email）
        if (Auth::attempt($credentials)) {
            // 用户登录成功
            return redirect('/admin');
        }else{
        // 用户登录失败，重定向回登录页面，并提供错误信息
        return redirect('/login')->with('error', 'Invalid credentials');
        }
    }
    public function logout(){
        Auth::logout();
        return  redirect('/login');
    }
}