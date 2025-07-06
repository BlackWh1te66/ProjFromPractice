<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DeviceCategory;

class DeviceCategoryController extends Controller
{
    public function store(Request $request)
    {
        $cat = DeviceCategory::create(['name' => $request->name]);
        return response()->json(['success' => true, 'category' => $cat]);
    }

    public function update(Request $request, $id)
    {
        $cat = DeviceCategory::findOrFail($id);
        $cat->name = $request->name;
        $cat->save();
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $cat = DeviceCategory::findOrFail($id);
        $cat->delete();
        return response()->json(['success' => true]);
    }
}