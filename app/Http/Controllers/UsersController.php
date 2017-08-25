<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Mail;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', [            
            'except' => ['show', 'create', 'store', 'index']
        ]);

        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

    //用户注册页面
    public function create()
    {
        return view('users.create');
    }

    //用户信息显示
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    //用户注册功能
    public function store(Request $request)
    {
    	$this->validate($request, [
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $this->sendEmailConfirmationTo($user);
        session()->flash('success', '验证邮件已发送到你的注册邮箱上，请注意查收。');
        //session()用于临时保存用户数据的方法.当我们想存入一条缓存的数据,让它只在下一次的请求内有效时,则可以使用 flash 方法.之后我们可以使用 session()->get('success') 通过键名来取出对应会话中的数据
        return view('/');
        // 两种返回方法都可以
        // return redirect()->route('users.show', [$user]);
    }

    //用户信息编辑页面
    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    //用户信息编辑功能
    public function update(User $user, Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50',
            'password' => 'nullable|confirmed|min:6'
        ]);

        $this->authorize('update', $user);

        $data = [];
        $data['name'] = $request->name;
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }
        $user->update($data);

        session()->flash('success', '个人资料更新成功！');
        return redirect()->route('users.show', $user->id);
    }

    //用户列表
    public function index()
    {
        $users = User::paginate(5);
        return view('users.index', compact('users'));
    }

    // 用户删除操作
    public function destroy(User $user)
    {
        $this->authorize('destroy', $user);
        $user->delete();
        session()->flash('success', '成功删除用户！');
        return back();
    }

    protected function sendEmailConfirmationTo(User $user)
    {
        $view = 'emails.confirm';
        $data = compact('user');
        $from = 'aufree@yousails.com';
        $name = 'Aufree';
        $to = $user->email;
        $subject = "感谢注册 Sample 应用！请确认你的邮箱。";

        Mail::send($view, $data, function ($message) use ($from, $name, $to, $subject) {
            $message->from($from, $name)->to($to)->subject($subject);
        });
    }
}





















