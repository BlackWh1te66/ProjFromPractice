<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceCategory;
use App\Models\DeviceCategory;

class ProfileAdminController extends Controller
{
    public function index()
    {
        $serviceCategories = ServiceCategory::all();
        $deviceCategories = DeviceCategory::all();
        $feedbacks = \DB::table('feedbacks')->orderByDesc('created_at')->get();
        return view('profile-admin', compact('serviceCategories', 'deviceCategories', 'feedbacks'));
    }
}