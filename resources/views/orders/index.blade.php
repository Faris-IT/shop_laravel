@extends('layouts.app')

@section('title', 'Pesanan Saya')

@section('content')
<div class="max-w-7xl mx-auto px-4">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">Pesanan Saya</h1>
    
    @if($orders->isEmpty())
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <i class="fas fa-shopping-bag text-6xl text-gray-400 mb-4"></i>
            <p class="text-gray-500 text-lg">Anda belum memiliki pesanan.</p>
            <a href="{{ route('home') }}" class="inline-block mt-4 bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                Mulai Belanja
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($orders as $order)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="border-l-4 border-blue-500 p-6">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4">
                            <div>
                                <span class="text-sm text-gray-500">No. Pesanan</span>
                                <h3 class="text-lg font-bold text-gray-800">{{ $order->order_number }}</h3>
                            </div>
                            <div class="mt-2 md:mt-0">
                                <span class="px-3 py-1 text-sm rounded-full 
                                    @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                                    @elseif($order->status == 'completed') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                                <span class="ml-2 px-3 py-1 text-sm rounded-full
                                    @if($order->payment_status == 'unpaid') bg-red-100 text-red-800
                                    @else bg-green-100 text-green-800 @endif">
                                    {{ $order->payment_status == 'unpaid' ? 'Belum Dibayar' : 'Sudah Dibayar' }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="grid md:grid-cols-3 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500">Tanggal</span>
                                <p>{{ $order->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500">Total</span>
                                <p class="font-bold text-blue-500">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500">Lokasi</span>
                                <p>{{ $order->city }}</p>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <a href="{{ route('orders.show', $order) }}" class="text-blue-500 hover:underline">
                                Lihat Detail <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection
