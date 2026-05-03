@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="max-w-7xl mx-auto px-4">
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="grid md:grid-cols-2 gap-8 p-6">
            <!-- Product Image -->
            <div>
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" 
                         alt="{{ $product->name }}" 
                         class="w-full rounded-lg">
                @else
                    <div class="w-full h-96 bg-gray-200 flex items-center justify-center rounded-lg">
                        <i class="fas fa-image text-gray-400 text-6xl"></i>
                    </div>
                @endif
            </div>
            
            <!-- Product Info -->
            <div>
                <div class="mb-4">
                    <span class="text-sm text-blue-500">{{ $product->category->name }}</span>
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $product->name }}</h1>
                    <div class="text-2xl font-bold text-blue-500">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </div>
                </div>
                
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-2">Deskripsi Produk</h3>
                    <p class="text-gray-600 leading-relaxed">{{ $product->description }}</p>
                </div>
                
                <div class="flex gap-4">
                    <button class="flex-1 bg-blue-500 text-white py-3 rounded-lg hover:bg-blue-600 transition">
                        <i class="fas fa-shopping-cart mr-2"></i> Beli Sekarang
                    </button>
                    <button class="flex-1 bg-gray-500 text-white py-3 rounded-lg hover:bg-gray-600 transition">
                        <i class="fas fa-heart mr-2"></i> Wishlist
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-8">
        <a href="{{ route('home') }}" class="text-blue-500 hover:underline">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Produk
        </a>
    </div>
</div>
@endsection
