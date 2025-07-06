<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use Illuminate\Support\Facades\Log;

class ServiceController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name'        => 'required|string|max:100',
                'category_id' => 'required|integer|exists:service_categories,id',
                'description' => 'nullable|string',
                'price'       => 'nullable|numeric',
                'duration_minutes' => 'nullable|integer',
                'image'       => 'nullable|image|max:4096',
            ]);

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('services', 'public');
                $validated['image'] = '/storage/' . $path;
            }

            $service = \App\Models\Service::create($validated);

            return response()->json(['success' => true, 'service' => $service]);
        } catch (\Throwable $e) {
            \Log::error('Service create error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Внутрішня помилка сервера: ' . $e->getMessage()
            ], 500);
        }
    }

    // Исправленный метод index для страницы
    public function index()
    {
        $serviceCategories = \App\Models\ServiceCategory::all();
        $services = \App\Models\Service::with('category')->get();
        return view('services', compact('serviceCategories', 'services'));
    }

    // Если нужен API-метод, добавьте:
    public function apiIndex()
    {
        return response()->json(\App\Models\Service::with('category')->get());
    }

    public function update(Request $request, \App\Models\Service $service)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            'category_id' => 'required|integer|exists:service_categories,id',
            'description' => 'nullable|string',
            'price'       => 'nullable|numeric',
            'duration_minutes' => 'nullable|integer',
            'image'       => 'nullable|image|max:4096',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('services', 'public');
            $validated['image'] = '/storage/' . $path;
        }

        $service->update($validated);
        return response()->json(['success' => true, 'service' => $service]);
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return response()->json(['success' => true]);
    }
}
