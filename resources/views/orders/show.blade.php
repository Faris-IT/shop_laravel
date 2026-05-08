@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $order->order_number)

@section('content')
<div class="max-w-4xl mx-auto px-4">
    <div class="mb-4">
        <a href="{{ route('orders.index') }}" class="text-blue-500 hover:underline">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Pesanan Saya
        </a>
    </div>
    
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
            <div class="flex justify-between items-center text-white">
                <div>
                    <h1 class="text-2xl font-bold">Detail Pesanan</h1>
                    <p class="text-blue-100">No. {{ $order->order_number }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm">Status Pesanan</p>
                    <span class="inline-block px-3 py-1 text-sm rounded-full 
                        @if($order->status == 'pending') bg-yellow-500
                        @elseif($order->status == 'processing') bg-blue-500
                        @elseif($order->status == 'completed') bg-green-500
                        @else bg-red-500 @endif">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <!-- Order Status Timeline -->
            <div class="mb-8">
                <h2 class="text-lg font-bold mb-4">Status Pesanan</h2>
                <div class="flex justify-between">
                    <div class="text-center">
                        <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center mx-auto">
                            <i class="fas fa-check text-white text-xs"></i>
                        </div>
                        <p class="text-xs mt-1">Pesanan Dibuat</p>
                        <p class="text-xs text-gray-500">{{ $order->created_at->format('d/m/Y') }}</p>
                    </div>
                    <div class="flex-1 h-1 bg-gray-300 mt-4 mx-2"></div>
                    <div class="text-center">
                        <div class="w-8 h-8 rounded-full {{ $order->status != 'pending' ? 'bg-green-500' : 'bg-gray-300' }} flex items-center justify-center mx-auto">
                            <i class="fas {{ $order->status != 'pending' ? 'fa-check' : 'fa-clock' }} text-white text-xs"></i>
                        </div>
                        <p class="text-xs mt-1">Diproses</p>
                    </div>
                    <div class="flex-1 h-1 bg-gray-300 mt-4 mx-2"></div>
                    <div class="text-center">
                        <div class="w-8 h-8 rounded-full {{ $order->status == 'completed' ? 'bg-green-500' : 'bg-gray-300' }} flex items-center justify-center mx-auto">
                            <i class="fas {{ $order->status == 'completed' ? 'fa-check' : 'fa-truck' }} text-white text-xs"></i>
                        </div>
                        <p class="text-xs mt-1">Selesai</p>
                    </div>
                </div>
            </div>
            
            <!-- Order Info -->
            <div class="grid md:grid-cols-2 gap-6 mb-8">
                <div>
                    <h2 class="text-lg font-bold mb-3">Informasi Pengiriman</h2>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="font-semibold">{{ $order->name }}</p>
                        <p class="text-gray-600">📞 {{ $order->phone }}</p>
                        <p class="text-gray-600 mt-2">📍 {{ $order->address }}</p>
                        <p class="text-gray-600">{{ $order->city }}, {{ $order->postal_code }}</p>
                    </div>
                </div>
                
                <div>
                    <h2 class="text-lg font-bold mb-3">Informasi Pembayaran</h2>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">Status Pembayaran</span>
                            <span class="font-semibold {{ $order->payment_status == 'paid' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $order->payment_status == 'paid' ? 'Sudah Dibayar' : 'Belum Dibayar' }}
                            </span>
                        </div>
                        @if($order->payment_status == 'unpaid')
                            <div class="mt-2 text-sm text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                Silakan lakukan pembayaran setelah admin mengkonfirmasi pesanan.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Order Items -->
            <h2 class="text-lg font-bold mb-4">Produk yang Dipesan</h2>
            <div class="border rounded-lg overflow-hidden mb-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Produk</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500">Jumlah</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">Harga</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($order->items as $item)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if($item->product && $item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" 
                                                 alt="{{ $item->product_name }}" 
                                                 class="w-12 h-12 object-cover rounded mr-3">
                                        @else
                                            <div class="w-12 h-12 bg-gray-200 rounded mr-3 flex items-center justify-center">
                                                <i class="fas fa-image text-gray-400"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-semibold">{{ $item->product_name }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">{{ $item->quantity }}</td>
                                <td class="px-6 py-4 text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-right font-semibold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-right font-bold">Total</td>
                            <td class="px-6 py-4 text-right font-bold text-blue-500">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                             </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            @if($order->notes)
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <p class="text-gray-600"><span class="font-semibold">Catatan:</span> {{ $order->notes }}</p>
                </div>
            @endif
            
            <!-- Payment Section -->
            <div class="mt-6">
                <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
                    <h2 class="text-xl font-bold mb-4">
                        <i class="fas fa-credit-card mr-2"></i>
                        Informasi Pembayaran
                    </h2>
                    
                    @if($order->payment)
                        <div class="space-y-3">
                            <div class="flex justify-between items-center pb-2 border-b">
                                <span class="text-gray-600">Status Pembayaran:</span>
                                <span class="px-3 py-1 rounded-full text-sm {{ $order->payment->status_badge ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $order->payment->status_text ?? ucfirst($order->payment->status) }}
                                </span>
                            </div>
                            
                            <div class="flex justify-between items-center pb-2 border-b">
                                <span class="text-gray-600">Metode Pembayaran:</span>
                                <span class="font-semibold">
                                    @if($order->payment->payment_method == 'bank_transfer')
                                        <i class="fas fa-university text-blue-500 mr-1"></i> Transfer Bank
                                    @elseif($order->payment->payment_method == 'ewallet')
                                        <i class="fas fa-wallet text-green-500 mr-1"></i> E-Wallet
                                    @else
                                        {{ ucfirst($order->payment->payment_method) }}
                                    @endif
                                    - {{ $order->payment->payment_provider }}
                                </span>
                            </div>
                            
                            <div class="flex justify-between items-center pb-2 border-b">
                                <span class="text-gray-600">Tanggal Pembayaran:</span>
                                <span>{{ $order->payment->payment_date ? $order->payment->payment_date->format('d/m/Y H:i') : '-' }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center pb-2 border-b">
                                <span class="text-gray-600">Jumlah Dibayar:</span>
                                <span class="font-bold text-green-600">Rp {{ number_format($order->payment->amount, 0, ',', '.') }}</span>
                            </div>
                            
                            @if($order->payment->proof_of_payment)
                                <div class="pb-2 border-b">
                                    <span class="text-gray-600">Bukti Pembayaran:</span>
                                    <a href="{{ asset('storage/' . $order->payment->proof_of_payment) }}" 
                                       target="_blank" 
                                       class="ml-2 text-blue-500 hover:underline">
                                        <i class="fas fa-file-alt mr-1"></i> Lihat Bukti Pembayaran
                                    </a>
                                </div>
                            @endif
                            
                            @if($order->payment->notes)
                                <div class="pb-2 border-b">
                                    <span class="text-gray-600">Catatan Pembayaran:</span>
                                    <p class="text-gray-700 mt-1">{{ $order->payment->notes }}</p>
                                </div>
                            @endif
                            
                            @if($order->payment->admin_notes)
                                <div class="bg-yellow-50 p-3 rounded-lg mt-2">
                                    <span class="text-yellow-800 font-semibold">📝 Catatan Admin:</span>
                                    <p class="text-yellow-700 mt-1">{{ $order->payment->admin_notes }}</p>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-6">
                            <i class="fas fa-clock text-5xl text-yellow-500 mb-3"></i>
                            <p class="text-gray-600">Belum ada pembayaran</p>
                            @if($order->canBePaid())
                                <a href="{{ route('payment.show', $order) }}" 
                                   class="inline-block mt-4 bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600 transition">
                                    <i class="fas fa-credit-card mr-2"></i> Bayar Sekarang
                                </a>
                            @elseif($order->payment && $order->payment->status == 'rejected')
                                <div class="mt-4 bg-red-50 border border-red-300 rounded-lg p-4 text-sm">
                                    <p class="text-red-600 font-semibold">Pembayaran Ditolak</p>
                                    <p class="text-red-600 mt-1">{{ $order->payment->admin_notes }}</p>
                                    <a href="{{ route('payment.show', $order) }}" 
                                       class="inline-block mt-3 bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                                        <i class="fas fa-upload mr-2"></i> Upload Ulang Bukti Pembayaran
                                    </a>
                                </div>
                            @elseif($order->status == 'cancelled')
                                <div class="mt-4 bg-red-50 border border-red-300 rounded-lg p-4">
                                    <p class="text-red-600">Pesanan ini telah dibatalkan dan tidak dapat dibayar.</p>
                                </div>
                            @elseif($order->status == 'completed')
                                <div class="mt-4 bg-green-50 border border-green-300 rounded-lg p-4">
                                    <p class="text-green-600">Pesanan ini sudah selesai.</p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Admin Actions -->
            @if(auth()->user()->isAdmin())
                <div class="border-t pt-6 mt-6">
                    <h3 class="text-lg font-bold mb-4">Update Status Pesanan (Admin)</h3>
                    <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="flex flex-wrap gap-4">
                        @csrf
                        @method('PUT')
                        <select name="status" class="px-3 py-2 border rounded focus:outline-none focus:border-blue-500">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        <select name="payment_status" class="px-3 py-2 border rounded focus:outline-none focus:border-blue-500">
                            <option value="unpaid" {{ $order->payment_status == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                            <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                        </select>
                        <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600 transition">
                            <i class="fas fa-save mr-2"></i> Update Status
                        </button>
                    </form>
                </div>
                
                <!-- Admin Payment Verification -->
                @if($order->payment && $order->payment->status == 'pending')
                    <div class="border-t pt-6 mt-6">
                        <h3 class="text-lg font-bold mb-4">Verifikasi Pembayaran (Admin)</h3>
                        
                        <div class="bg-yellow-50 border border-yellow-300 rounded-lg p-4 mb-4">
                            <p class="font-semibold text-yellow-800">⚠️ Menunggu verifikasi pembayaran</p>
                            <p class="text-sm text-yellow-700 mt-1">
                                Pembayaran dari: <strong>{{ $order->payment->payment_provider }}</strong> 
                                ({{ $order->payment->payment_method == 'bank_transfer' ? 'Transfer Bank' : 'E-Wallet' }})
                            </p>
                            <p class="text-sm text-yellow-700">
                                Jumlah: <strong>Rp {{ number_format($order->payment->amount, 0, ',', '.') }}</strong>
                            </p>
                            @if($order->payment->proof_of_payment)
                                <a href="{{ asset('storage/' . $order->payment->proof_of_payment) }}" 
                                   target="_blank" 
                                   class="inline-block mt-2 text-blue-600 hover:underline">
                                    <i class="fas fa-file-alt mr-1"></i> Lihat Bukti Pembayaran
                                </a>
                            @endif
                        </div>
                        
                        <div class="grid md:grid-cols-2 gap-4">
                            <form action="{{ route('admin.payments.verify', $order->payment) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="admin_notes_verify" class="block text-sm font-bold mb-1">Catatan (Opsional)</label>
                                    <textarea name="admin_notes" id="admin_notes_verify" rows="2" 
                                              class="w-full px-3 py-2 border rounded focus:outline-none focus:border-green-500 text-sm"
                                              placeholder="Tambahkan catatan jika perlu..."></textarea>
                                </div>
                                <button type="submit" class="w-full bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition">
                                    <i class="fas fa-check-circle mr-2"></i> Verifikasi Pembayaran
                                </button>
                            </form>
                            
                            <form action="{{ route('admin.payments.reject', $order->payment) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="admin_notes_reject" class="block text-sm font-bold mb-1">Alasan Penolakan *</label>
                                    <textarea name="admin_notes" id="admin_notes_reject" rows="2" 
                                              class="w-full px-3 py-2 border rounded focus:outline-none focus:border-red-500 text-sm"
                                              required placeholder="Contoh: Bukti transfer tidak jelas / nominal tidak sesuai"></textarea>
                                </div>
                                <button type="submit" class="w-full bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">
                                    <i class="fas fa-times-circle mr-2"></i> Tolak Pembayaran
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection
