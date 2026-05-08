<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    // Show payment page
    public function show(Order $order)
    {
        // Check if order belongs to current user
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Check if order can be paid
        if (!$order->canBePaid()) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Pesanan ini tidak dapat dibayar lagi.');
        }
        
        // Get existing payment or create new
        $payment = $order->payment;
        
        // Bank account info
        $bankAccounts = [
            ['bank' => 'BCA', 'account_number' => '1234567890', 'account_name' => 'PT Online Shop'],
            ['bank' => 'Mandiri', 'account_number' => '9876543210', 'account_name' => 'PT Online Shop'],
            ['bank' => 'BRI', 'account_number' => '5555555555', 'account_name' => 'PT Online Shop'],
        ];
        
        // E-wallet info
        $ewallets = [
            ['name' => 'GoPay', 'number' => '081234567890', 'qr_code' => null],
            ['name' => 'OVO', 'number' => '081234567891', 'qr_code' => null],
            ['name' => 'DANA', 'number' => '081234567892', 'qr_code' => null],
        ];
        
        return view('payment.show', compact('order', 'payment', 'bankAccounts', 'ewallets'));
    }
    
    // Upload payment proof
    public function uploadProof(Request $request, Order $order)
    {
        // Check if order belongs to current user
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        
        $request->validate([
            'payment_method' => 'required|in:bank_transfer,ewallet',
            'payment_provider' => 'required|string',
            'proof_of_payment' => 'required|image|mimes:jpeg,png,jpg,pdf|max:2048',
            'notes' => 'nullable|string',
        ]);
        
        DB::beginTransaction();
        
        try {
            // Upload proof file
            if ($request->hasFile('proof_of_payment')) {
                $file = $request->file('proof_of_payment');
                $filename = time() . '_' . $order->order_number . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('payment_proofs', $filename, 'public');
            }
            
            // Create or update payment
            $payment = Payment::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'payment_method' => $request->payment_method,
                    'payment_provider' => $request->payment_provider,
                    'proof_of_payment' => $path ?? null,
                    'amount' => $order->total_amount,
                    'status' => 'pending',
                    'notes' => $request->notes,
                    'payment_date' => now(),
                ]
            );
            
            DB::commit();
            
            return redirect()->route('orders.show', $order)
                ->with('success', 'Bukti pembayaran berhasil diupload. Mohon tunggu verifikasi dari admin.');
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }
    
    // Admin: Verify payment
    public function verify(Payment $payment)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        
        DB::beginTransaction();
        
        try {
            $payment->update([
                'status' => 'verified',
                'admin_notes' => request('admin_notes'),
            ]);
            
            $payment->order->update([
                'payment_status' => 'paid',
                'status' => 'processing',
            ]);
            
            DB::commit();
            
            return back()->with('success', 'Pembayaran berhasil diverifikasi!');
            
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }
    
    // Admin: Reject payment
    public function reject(Payment $payment)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        
        $request = request();
        $request->validate([
            'admin_notes' => 'required|string|min:10',
        ]);
        
        DB::beginTransaction();
        
        try {
            $payment->update([
                'status' => 'rejected',
                'admin_notes' => $request->admin_notes,
            ]);
            
            $payment->order->update([
                'payment_status' => 'unpaid',
            ]);
            
            DB::commit();
            
            return back()->with('error', 'Pembayaran ditolak. Alasan: ' . $request->admin_notes);
            
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }
}
