<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $casts = [
        'total_harga' => 'decimal:2',
        'order_date'  => 'datetime',
    ];

    protected $fillable = [
        'user_id',
        'event_id',
        'order_date',
        'total_harga',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    // relasi detail order (hasMany)
    public function detailOrders()
    {
        return $this->hasMany(DetailOrder::class, 'order_id');
    }

    // alias biar bisa kamu panggil $history->details
    public function details()
    {
        return $this->hasMany(DetailOrder::class, 'order_id');
    }

    // kalau kamu butuh many-to-many tiket via pivot detail_orders
    public function tikets()
    {
        return $this->belongsToMany(Tiket::class, 'detail_orders', 'order_id', 'tiket_id')
            ->withPivot('jumlah', 'subtotal_harga');
    }
}
