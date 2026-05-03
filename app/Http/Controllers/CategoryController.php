<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $this->authorizeAdmin();
        $categories = Category::withCount('products')->get();
        return view('admin.categories.index', compact('categories'));
    }
    
    public function store(Request $request)
    {
        $this->authorizeAdmin();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
        ]);
        
        $validated['slug'] = Str::slug($request->name);
        
        Category::create($validated);
        
        return redirect()->route('admin.categories')->with('success', 'Kategori berhasil ditambahkan');
    }
    
    public function update(Request $request, Category $category)
    {
        $this->authorizeAdmin();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);
        
        $validated['slug'] = Str::slug($request->name);
        
        $category->update($validated);
        
        return redirect()->route('admin.categories')->with('success', 'Kategori berhasil diupdate');
    }
    
    public function destroy(Category $category)
    {
        $this->authorizeAdmin();
        
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus kategori yang memiliki produk');
        }
        
        $category->delete();
        
        return redirect()->route('admin.categories')->with('success', 'Kategori berhasil dihapus');
    }
    
    private function authorizeAdmin()
    {
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }
    }
}