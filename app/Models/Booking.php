<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'user_id',
        'sales_price',
        'purchase_price',
        'arrival_date',
        'purchase_day',
        'nights',
        'status',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function capacity(): BelongsTo
    {
        return $this->belongsTo(Capacity::class, 'hotel_id', 'hotel_id');
    }
}
