@extends('layouts.app')

@section('title', 'Produk - Online Shop')

@section('content')
<div class="max-w-7xl mx-auto px-4">
    <div class="flex flex-col md:flex-row gap-8">
        <!-- Sidebar Filter -->
        <div class="md:w-1/4">
            <div class="bg-white rounded-lg shadow-md p-4">
                <h3 class="text-lg font-bold mb-4">Filter Kategori</h3>
                <div class="space-y-2">
                    <a href="{{ route('home') }}" 
                       class="block text-gray-700 hover:text-blue-500 {{ !request('category') ? 'font-bold text-blue-500' : '' }}">
                        Semua Produk
                    </a>
                    @foreach($categories as $category)
                        <a href="?category={{ $category->id }}" 
                           class="block text-gray-700 hover:text-blue-500 {{ request('category') == $category->id ? 'font-bold text-blue-500' : '' }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        
        <!-- Product Grid -->
        <div class="md:w-3/4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($products as $product)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <i class="fas fa-image text-gray-400 text-4xl"></i>
                            </div>
                        @endif
                        
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $product->name }}</h3>
                            <p class="text-gray-600 text-sm mb-2">{{ Str::limit($product->description, 100) }}</p>
                            <div class="flex justify-between items-center">
                                <span class="text-xl font-bold text-blue-500">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                <a href="{{ route('product.show', $product) }}" 
                                   class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                    Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-8">
                        <p class="text-gray-500">Belum ada produk.</p>
                    </div>
                @endforelse
            </div>
            
            <div class="mt-6">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
