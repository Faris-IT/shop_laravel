<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // List user orders
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
                      ->orderBy('created_at', 'desc')
                      ->paginate(10);
        
        return view('orders.index', compact('orders'));
    }
    
    // Show order details
    public function show(Order $order)
    {
        // Check if order belongs to current user or user is admin
        if ($order->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }
        
        $order->load('items.product');
        
        return view('orders.show', compact('order'));
    }
    
    // Admin: List all orders
    public function adminIndex()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        
        $orders = Order::with('user')
                      ->orderBy('created_at', 'desc')
                      ->paginate(20);
        
        return view('admin.orders.index', compact('orders'));
    }
    
    // Admin: Update order status
    public function updateStatus(Request $request, Order $order)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
            'payment_status' => 'required|in:unpaid,paid'
        ]);
        
        $order->update([
            'status' => $request->status,
            'payment_status' => $request->payment_status
        ]);
        
        return redirect()->route('admin.orders')->with('success', 'Status pesanan berhasil diupdate!');
    }
}
