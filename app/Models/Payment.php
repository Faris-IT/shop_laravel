<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'order_id', 'payment_method', 'payment_provider', 'proof_of_payment',
        'amount', 'status', 'payment_date', 'notes', 'admin_notes'
    ];
    
    protected $casts = [
        'payment_date' => 'datetime',
        'amount' => 'decimal:2'
    ];
    
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
    
    // Get status badge color
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'verified' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
    
    // Get status text
    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'pending' => 'Menunggu Verifikasi',
            'verified' => 'Terverifikasi',
            'rejected' => 'Ditolak',
            default => 'Unknown',
        };
    }
}
