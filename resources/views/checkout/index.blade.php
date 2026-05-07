@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="max-w-7xl mx-auto px-4">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">Checkout</h1>
    
    <div class="grid lg:grid-cols-3 gap-8">
        <!-- Checkout Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold mb-4">Informasi Pengiriman</h2>
                
                <form action="{{ route('checkout.store') }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="name" class="block text-gray-700 font-bold mb-2">Nama Lengkap *</label>
                            <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}" 
                                   class="w-full px-3 py-2 border rounded focus:outline-none focus:border-blue-500 @error('name') border-red-500 @enderror"
                                   required>
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        
                        <div>
                            <label for="phone" class="block text-gray-700 font-bold mb-2">No. Telepon *</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                   class="w-full px-3 py-2 border rounded focus:outline-none focus:border-blue-500 @error('phone') border-red-500 @enderror"
                                   required>
                            @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="address" class="block text-gray-700 font-bold mb-2">Alamat Lengkap *</label>
                        <textarea name="address" id="address" rows="3" 
                                  class="w-full px-3 py-2 border rounded focus:outline-none focus:border-blue-500 @error('address') border-red-500 @enderror"
                                  required>{{ old('address') }}</textarea>
                        @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label for="city" class="block text-gray-700 font-bold mb-2">Kota *</label>
                            <input type="text" name="city" id="city" value="{{ old('city') }}"
                                   class="w-full px-3 py-2 border rounded focus:outline-none focus:border-blue-500 @error('city') border-red-500 @enderror"
                                   required>
                            @error('city') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        
                        <div>
                            <label for="postal_code" class="block text-gray-700 font-bold mb-2">Kode Pos *</label>
                            <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code') }}"
                                   class="w-full px-3 py-2 border rounded focus:outline-none focus:border-blue-500 @error('postal_code') border-red-500 @enderror"
                                   required>
                            @error('postal_code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <label for="notes" class="block text-gray-700 font-bold mb-2">Catatan (Opsional)</label>
                        <textarea name="notes" id="notes" rows="2" 
                                  class="w-full px-3 py-2 border rounded focus:outline-none focus:border-blue-500"
                                  placeholder="Contoh: Pintu belakang, dll">{{ old('notes') }}</textarea>
                    </div>
                    
                    <div class="flex gap-4">
                        <a href="{{ route('cart.index') }}" class="px-6 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                            Kembali
                        </a>
                        <button type="submit" class="flex-1 bg-green-500 text-white py-2 rounded hover:bg-green-600">
                            <i class="fas fa-check-circle mr-2"></i> Konfirmasi Pesanan
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Order Summary -->
        <div>
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                <h2 class="text-xl font-bold mb-4">Ringkasan Pesanan</h2>
                
                <div class="divide-y divide-gray-200">
                    @foreach($cartItems as $item)
                        <div class="py-3">
                            <div class="flex justify-between">
                                <span class="text-gray-700">{{ $item->product->name }}</span>
                                <span class="font-semibold">Rp {{ number_format($item->product->price, 0, ',', '.') }}</span>
                            </div>
                            <div class="text-sm text-gray-500">Jumlah: {{ $item->quantity }}</div>
                            <div class="text-right text-sm">Subtotal: Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</div>
                        </div>
                    @endforeach
                </div>
                
                <div class="border-t border-gray-200 mt-4 pt-4">
                    <div class="flex justify-between text-lg font-bold">
                        <span>Total</span>
                        <span class="text-green-500">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>
                
                <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                    <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                    <span class="text-sm text-gray-600">Pembayaran akan dilakukan setelah pesanan dikonfirmasi admin.</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
