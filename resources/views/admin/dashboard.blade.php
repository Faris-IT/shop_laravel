@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">Dashboard Admin</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Products Card -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="bg-blue-500 rounded-full p-3">
                    <i class="fas fa-box text-white text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-600 text-sm">Total Produk</p>
                    <p class="text-2xl font-bold">{{ $totalProducts }}</p>
                </div>
            </div>
        </div>
        
        <!-- Total Categories Card -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="bg-green-500 rounded-full p-3">
                    <i class="fas fa-tags text-white text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-600 text-sm">Total Kategori</p>
                    <p class="text-2xl font-bold">{{ $totalCategories }}</p>
                </div>
            </div>
        </div>
        
        <!-- Total Users Card -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="bg-purple-500 rounded-full p-3">
                    <i class="fas fa-users text-white text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-600 text-sm">Total Pengguna</p>
                    <p class="text-2xl font-bold">{{ $totalUsers }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="mt-8 bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold mb-4">Aksi Cepat</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="{{ route('admin.products.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded text-center hover:bg-blue-600">
                <i class="fas fa-plus mr-2"></i> Tambah Produk Baru
            </a>
            <a href="{{ route('admin.categories') }}" class="bg-green-500 text-white px-4 py-2 rounded text-center hover:bg-green-600">
                <i class="fas fa-folder-plus mr-2"></i> Kelola Kategori
            </a>
        </div>
    </div>
</div>
@endsection
