<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class SessionsController extends Controller
{
    public function __construct()
    {
        // 只有未登录才可以访问登录页面
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

	  //用户登录页面
    public function create()
    {
        return view('sessions.create');
    }

    //用户登录功能
    public function store(Request $request)
    {
       $this->validate($request, [
           'email' => 'required|email|max:255',
           'password' => 'required'
       ]);

       $credentials = [
           'email'    => $request->email,
           'password' => $request->password,
       ];       

       //第二个参数是记住我功能，记住我登录状态可长达5年。第一个参数是需要验证的数据
       if (Auth::attempt($credentials, $request->has('remember'))) {
           if (Auth::user()->activated){
              session()->flash('success', '欢迎回来！');
              // intended 该方法可将页面重定向到上一次请求尝试访问的页面上
              return redirect()->intended(route('users.show', [Auth::user()]));
           } else {
              Auth::logout();
              session()->flash('warning', '你的账号未激活，请检查邮箱中的注册邮件进行激活');
              return redirect('/');
           }
           
       } else {
           session()->flash('danger', '很抱歉，您的邮箱和密码不匹配');
           return redirect()->back();
       }
    }

    //用户退出
    public function destroy()
    {
        Auth::logout();
        session()->flash('success', '您已成功退出！');
        return redirect('login');
    }
}
