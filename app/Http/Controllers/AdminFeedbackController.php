<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminFeedbackController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:100',
            'phone'   => 'required|string|max:30',
            'email'   => 'nullable|email|max:150',
            'service' => 'nullable|string|max:100',
            'message' => 'nullable|string|max:2000',
        ]);

        // Сохраняем в таблицу feedbacks
        \DB::table('feedbacks')->insert([
            'user_id'  => \Auth::id(),
            'name'     => $validated['name'],
            'phone'    => $validated['phone'],
            'email'    => $validated['email'] ?? null,
            'service'  => $validated['service'] ?? null, // здесь сохраняется значение из поля формы "service"
            'message'  => $validated['message'] ?? null,
            'created_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Ваше повідомлення надіслано адміністратору!');
    }

    public function index()
    {
        $feedbacks = \DB::table('feedbacks')->orderByDesc('created_at')->get();
        return view('admin-feedbacks', compact('feedbacks'));
    }
}
