<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'transaction_id',
        'amount',
        'status',
        'payment_method',
        'customer_name',
        'customer_email',
        'customer_phone',
        'khalti_response'
    ];

    protected $casts = [
        'khalti_response' => 'array'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}