<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'user_id', 'name', 'phone', 'address', 
        'city', 'postal_code', 'total_amount', 'status', 
        'payment_status', 'notes'
    ];
    
    protected $casts = [
        'total_amount' => 'decimal:2',
    ];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
    
    // Generate unique order number
    public static function generateOrderNumber()
    {
        $prefix = 'INV-' . date('Ymd');
        $lastOrder = self::where('order_number', 'like', $prefix . '%')
                        ->orderBy('id', 'desc')
                        ->first();
        
        if (!$lastOrder) {
            $number = $prefix . '-0001';
        } else {
            $lastNumber = explode('-', $lastOrder->order_number);
            $lastIncrement = end($lastNumber);
            $newIncrement = str_pad((int)$lastIncrement + 1, 4, '0', STR_PAD_LEFT);
            $number = $prefix . '-' . $newIncrement;
        }
        
        return $number;
    }
}
