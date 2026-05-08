@extends('layouts.app')

@section('title', 'Pembayaran - ' . $order->order_number)

@section('content')
<div class="max-w-7xl mx-auto px-4">
    <div class="mb-4">
        <a href="{{ route('orders.show', $order) }}" class="text-blue-500 hover:underline">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Detail Pesanan
        </a>
    </div>
    
    <div class="grid lg:grid-cols-3 gap-8">
        <!-- Payment Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                    <h1 class="text-2xl font-bold text-white">Pembayaran</h1>
                    <p class="text-blue-100">Pesanan #{{ $order->order_number }}</p>
                </div>
                
                <div class="p-6">
                    <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-400 p-4">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-yellow-500 mt-0.5 mr-3"></i>
                            <div>
                                <p class="text-sm text-yellow-700">
                                    <strong>Total yang harus dibayar:</strong> 
                                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </p>
                                <p class="text-sm text-yellow-700 mt-1">
                                    Silakan transfer sesuai total di atas. Upload bukti pembayaran untuk verifikasi.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <form action="{{ route('payment.upload', $order) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Payment Method -->
                        <div class="mb-6">
                            <label class="block text-gray-700 font-bold mb-3">Metode Pembayaran</label>
                            <div class="grid md:grid-cols-2 gap-4">
                                <label class="border rounded-lg p-4 cursor-pointer hover:border-blue-500 transition payment-method">
                                    <input type="radio" name="payment_method" value="bank_transfer" class="mr-2" required>
                                    <i class="fas fa-university text-blue-500 mr-2"></i>
                                    <span>Transfer Bank</span>
                                </label>
                                <label class="border rounded-lg p-4 cursor-pointer hover:border-blue-500 transition payment-method">
                                    <input type="radio" name="payment_method" value="ewallet" class="mr-2" required>
                                    <i class="fas fa-wallet text-green-500 mr-2"></i>
                                    <span>E-Wallet</span>
                                </label>
                            </div>
                            @error('payment_method')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Payment Provider -->
                        <div class="mb-6" id="bank_transfer_fields" style="display: none;">
                            <label for="payment_provider_bank" class="block text-gray-700 font-bold mb-2">Pilih Bank</label>
                            <select name="payment_provider" id="payment_provider_bank" class="w-full px-3 py-2 border rounded">
                                <option value="">Pilih Bank Tujuan</option>
                                @foreach($bankAccounts as $bank)
                                    <option value="{{ $bank['bank'] }}">{{ $bank['bank'] }} - {{ $bank['account_number'] }} ({{ $bank['account_name'] }})</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-6" id="ewallet_fields" style="display: none;">
                            <label for="payment_provider_ewallet" class="block text-gray-700 font-bold mb-2">Pilih E-Wallet</label>
                            <select name="payment_provider" id="payment_provider_ewallet" class="w-full px-3 py-2 border rounded">
                                <option value="">Pilih E-Wallet</option>
                                @foreach($ewallets as $wallet)
                                    <option value="{{ $wallet['name'] }}">{{ $wallet['name'] }} - {{ $wallet['number'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Upload Proof -->
                        <div class="mb-6">
                            <label for="proof_of_payment" class="block text-gray-700 font-bold mb-2">
                                Upload Bukti Pembayaran
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                                <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                                <p class="text-gray-600">Klik atau drag file ke sini</p>
                                <p class="text-gray-500 text-sm">Format: JPG, PNG, PDF (Max 2MB)</p>
                                <input type="file" name="proof_of_payment" id="proof_of_payment" class="hidden" accept="image/*,application/pdf" required>
                                <button type="button" onclick="document.getElementById('proof_of_payment').click()" 
                                        class="mt-3 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                    Pilih File
                                </button>
                                <div id="file_info" class="mt-2 text-sm text-green-600"></div>
                            </div>
                            @error('proof_of_payment')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Notes -->
                        <div class="mb-6">
                            <label for="notes" class="block text-gray-700 font-bold mb-2">Catatan (Opsional)</label>
                            <textarea name="notes" id="notes" rows="3" 
                                      class="w-full px-3 py-2 border rounded focus:outline-none focus:border-blue-500"
                                      placeholder="Contoh: Transfer dari BCA a.n. John Doe"></textarea>
                        </div>
                        
                        <button type="submit" class="w-full bg-green-500 text-white py-3 rounded-lg hover:bg-green-600 transition">
                            <i class="fas fa-upload mr-2"></i> Upload Bukti Pembayaran
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Payment Info -->
        <div>
            <!-- Bank Account Info -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-bold mb-4">
                    <i class="fas fa-university text-blue-500 mr-2"></i> 
                    Rekening Bank
                </h2>
                <div class="space-y-4">
                    @foreach($bankAccounts as $bank)
                        <div class="border rounded-lg p-3">
                            <p class="font-bold">{{ $bank['bank'] }}</p>
                            <p class="text-gray-600">{{ $bank['account_number'] }}</p>
                            <p class="text-sm text-gray-500">a.n. {{ $bank['account_name'] }}</p>
                            <button onclick="copyToClipboard('{{ $bank['account_number'] }}')" 
                                    class="mt-2 text-xs bg-gray-100 px-2 py-1 rounded hover:bg-gray-200">
                                <i class="fas fa-copy mr-1"></i> Salin No. Rekening
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <!-- E-Wallet Info -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold mb-4">
                    <i class="fas fa-wallet text-green-500 mr-2"></i> 
                    E-Wallet
                </h2>
                <div class="space-y-4">
                    @foreach($ewallets as $wallet)
                        <div class="border rounded-lg p-3">
                            <p class="font-bold">{{ $wallet['name'] }}</p>
                            <p class="text-gray-600">{{ $wallet['number'] }}</p>
                            <button onclick="copyToClipboard('{{ $wallet['number'] }}')" 
                                    class="mt-2 text-xs bg-gray-100 px-2 py-1 rounded hover:bg-gray-200">
                                <i class="fas fa-copy mr-1"></i> Salin No. E-Wallet
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Toggle payment method fields
document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.getElementById('bank_transfer_fields').style.display = 'none';
        document.getElementById('ewallet_fields').style.display = 'none';
        
        if (this.value === 'bank_transfer') {
            document.getElementById('bank_transfer_fields').style.display = 'block';
            document.getElementById('payment_provider_bank').required = true;
            document.getElementById('payment_provider_ewallet').required = false;
        } else if (this.value === 'ewallet') {
            document.getElementById('ewallet_fields').style.display = 'block';
            document.getElementById('payment_provider_bank').required = false;
            document.getElementById('payment_provider_ewallet').required = true;
        }
    });
});

// File input preview
document.getElementById('proof_of_payment').addEventListener('change', function(e) {
    const fileName = e.target.files[0]?.name;
    if (fileName) {
        document.getElementById('file_info').innerHTML = `<i class="fas fa-check-circle mr-1"></i> ${fileName}`;
    } else {
        document.getElementById('file_info').innerHTML = '';
    }
});

// Copy to clipboard function
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert('Nomor berhasil disalin: ' + text);
    }).catch(() => {
        alert('Gagal menyalin nomor');
    });
}
</script>
@endsection
