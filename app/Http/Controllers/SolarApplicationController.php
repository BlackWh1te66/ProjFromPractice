<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SolarApplication;

class SolarApplicationController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'phone'         => 'required|string|max:50',
            'email'         => 'nullable|email|max:255',
            'location'      => 'required|string|max:255',
            'system_config' => 'nullable|string|max:255',
            'message'       => 'nullable|string',
        ]);

        SolarApplication::create($validated);

        return response()->json(['success' => true]);
    }

    public function index()
    {
        return response()->json(\App\Models\SolarApplication::orderBy('created_at', 'desc')->get());
    }
}
