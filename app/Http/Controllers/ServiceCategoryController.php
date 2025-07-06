<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceCategory;

class ServiceCategoryController extends Controller
{
    public function store(Request $request)
    {
        $cat = ServiceCategory::create(['name' => $request->name]);
        return response()->json(['success' => true, 'category' => $cat]);
    }

    public function update(Request $request, $id)
    {
        $cat = ServiceCategory::findOrFail($id);
        $cat->name = $request->name;
        $cat->save();
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $cat = ServiceCategory::findOrFail($id);
        $cat->delete();
        return response()->json(['success' => true]);
    }
}