@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="max-w-7xl mx-auto px-4">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">Keranjang Belanja</h1>
    
    @if($cartItems->isEmpty())
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <i class="fas fa-shopping-cart text-6xl text-gray-400 mb-4"></i>
            <p class="text-gray-500 text-lg">Keranjang belanja Anda kosong.</p>
            <a href="{{ route('home') }}" class="inline-block mt-4 bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                Belanja Sekarang
            </a>
        </div>
    @else
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Cart Items -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="divide-y divide-gray-200">
                        @foreach($cartItems as $item)
                            <div class="p-6">
                                <div class="flex flex-col sm:flex-row gap-4">
                                    <!-- Product Image -->
                                    <div class="sm:w-32">
                                        @if($item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" 
                                                 alt="{{ $item->product->name }}"
                                                 class="w-full h-32 object-cover rounded">
                                        @else
                                            <div class="w-full h-32 bg-gray-200 rounded flex items-center justify-center">
                                                <i class="fas fa-image text-gray-400 text-2xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Product Info -->
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-800">{{ $item->product->name }}</h3>
                                        <p class="text-gray-600 text-sm mt-1">{{ $item->product->category->name }}</p>
                                        <div class="text-blue-500 font-bold mt-2">
                                            Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                        </div>
                                        
                                        <!-- Quantity & Actions -->
                                        <div class="flex items-center justify-between mt-4">
                                            <form action="{{ route('cart.update', $item) }}" method="POST" class="flex items-center gap-2">
                                                @csrf
                                                @method('PUT')
                                                <input type="number" name="quantity" value="{{ $item->quantity }}" 
                                                       min="1" max="99" class="w-20 px-2 py-1 border rounded text-center">
                                                <button type="submit" class="text-blue-500 hover:text-blue-700">
                                                    <i class="fas fa-sync-alt"></i> Update
                                                </button>
                                            </form>
                                            
                                            <form action="{{ route('cart.destroy', $item) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700" 
                                                        onclick="return confirm('Hapus produk ini dari keranjang?')">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    
                                    <!-- Subtotal -->
                                    <div class="sm:w-32 text-right">
                                        <p class="text-gray-600 text-sm">Subtotal:</p>
                                        <p class="text-lg font-bold text-gray-800">
                                            Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="mt-4 flex justify-between">
                    <a href="{{ route('home') }}" class="text-blue-500 hover:underline">
                        <i class="fas fa-shopping-bag mr-2"></i> Lanjutkan Belanja
                    </a>
                    <form action="{{ route('cart.clear') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700" 
                                onclick="return confirm('Kosongkan semua keranjang?')">
                            <i class="fas fa-trash-alt mr-2"></i> Kosongkan Keranjang
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Cart Summary -->
            <div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold mb-4">Ringkasan Belanja</h2>
                    
                    <div class="space-y-2 border-b pb-4">
                        @foreach($cartItems as $item)
                            <div class="flex justify-between text-sm">
                                <span>{{ $item->product->name }} x{{ $item->quantity }}</span>
                                <span>Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="flex justify-between text-lg font-bold mt-4 pt-2">
                        <span>Total</span>
                        <span class="text-blue-500">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    
                    <a href="{{ route('checkout.index') }}" class="block w-full bg-green-500 text-white text-center py-3 rounded-lg hover:bg-green-600 transition mt-6">
                        <i class="fas fa-credit-card mr-2"></i> Checkout
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
