<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
       public function signIn(Request $request)
    {
        $login = $request->post('login');
        $password = $request->post('password');

        if (User::signIn($login, $password)) {
            return redirect()->route('admin.products')->with(['success' => 'Ви успішно увійшли']);
            
        }
        return redirect()->back()->with(['error' => 'Неправильний логін або пароль']);
    }

    public function logOut()
    {
        Auth::logout();

        return redirect('/');
    }
}
