<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users,username',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'birthday' => 'required|date',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required',
            'password' => 'required|min:6'
        ]);

        $user = User::create([
            'username'   => $request->username,
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'birthday'   => $request->birthday,
            'email'      => $request->email,
            'phone'      => $request->phone,
            'password'   => \Illuminate\Support\Facades\Hash::make($request->password),
            'role'       => 'user'
        ]);

        \Illuminate\Support\Facades\Auth::login($user);

        return response()->json(['success' => true]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Невірний логін або пароль'], 401);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/'); // редирект на главную страницу
    }
}
