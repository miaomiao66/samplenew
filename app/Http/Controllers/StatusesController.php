<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class StatusesController extends Controller
{
	// 需要登录之后才能执行
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'content' => 'required|max:140'
        ]);

        Auth::user()->statuses()->create([
            'content' => $request->content
        ]);
        // 导向至上一次发出请求的页面
        return redirect()->back();
    }

    // 删除微博
    public function destroy(Status $status)
    {
    	// 删除的授权检测，不通过会抛出 403 异常
        $this->authorize('destroy', $status);
        $status->delete();
        session()->flash('success', '微博已被成功删除！');
        return redirect()->back();
    }
}
