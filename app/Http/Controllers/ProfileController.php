<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('profile-user', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'first_name' => 'nullable|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:30',
            'birthday' => 'nullable|date',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user->first_name = $request->input('first_name');
        $user->last_name  = $request->input('last_name');
        $user->email      = $request->input('email');
        $user->phone      = $request->input('phone');
        $user->birthday   = $request->input('birthday');

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path; // путь сохраняется в базу
        }

        $user->save();

        return redirect()->route('profile.show')->with('success', 'Дані оновлено!');
    }

    public function changePassword(Request $request)
    {
        // Валидация: new_password и new_password_confirmation должны быть в запросе
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed', // Laravel ожидает new_password_confirmation
        ]);

        $user = auth()->user();

        // Проверка текущего пароля
        if (!\Hash::check($request->current_password, $user->password)) {
            return response()->json(['success' => false, 'message' => 'Поточний пароль невірний.'], 422);
        }

        // Смена пароля
        $user->password = \Hash::make($request->new_password);
        $user->save();

        return response()->json(['success' => true]);
    }
}
