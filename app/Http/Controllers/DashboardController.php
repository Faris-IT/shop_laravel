<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            abort(403);
        }
        
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalUsers = User::count();
        
        return view('admin.dashboard', compact('totalProducts', 'totalCategories', 'totalUsers'));
    }
}
