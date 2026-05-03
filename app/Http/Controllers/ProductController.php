<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // PUBLIC: List produk untuk customer (halaman home)
    public function publicIndex(Request $request)
    {
        $query = Product::with('category');
        
        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }
        
        $products = $query->latest()->paginate(12);
        $categories = Category::all();
        
        return view('products.index', compact('products', 'categories'));
    }
    
    // PUBLIC: Detail produk
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }
    
    // ADMIN: List produk untuk admin (dengan tabel management)
    public function adminIndex()
    {
        $this->authorizeAdmin();
        $products = Product::with('category')->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }
    
    // ADMIN: Form tambah produk
    public function create()
    {
        $this->authorizeAdmin();
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }
    
    // ADMIN: Store produk
    public function store(Request $request)
    {
        $this->authorizeAdmin();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        $validated['slug'] = \Str::slug($request->name);
        
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }
        
        Product::create($validated);
        
        return redirect()->route('admin.products')->with('success', 'Produk berhasil ditambahkan');
    }
    
    // ADMIN: Form edit
    public function edit(Product $product)
    {
        $this->authorizeAdmin();
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }
    
    // ADMIN: Update
    public function update(Request $request, Product $product)
    {
        $this->authorizeAdmin();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        $validated['slug'] = \Str::slug($request->name);
        
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($product->image) {
                \Storage::disk('public')->delete($product->image);
            }
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }
        
        $product->update($validated);
        
        return redirect()->route('admin.products')->with('success', 'Produk berhasil diupdate');
    }
    
    // ADMIN: Delete
    public function destroy(Product $product)
    {
        $this->authorizeAdmin();
        
        // Hapus gambar jika ada
        if ($product->image) {
            \Storage::disk('public')->delete($product->image);
        }
        
        $product->delete();
        
        return redirect()->route('admin.products')->with('success', 'Produk berhasil dihapus');
    }
    
    private function authorizeAdmin()
    {
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }
    }
}