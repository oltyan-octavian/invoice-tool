<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'name',
        'price_without_tax',
        'discount',
        'final_price',
        'user_id'
    ];

    protected static function booted()
    {
        static::creating(function ($item) {
            $price = floatval($item->price_without_tax ?? 0);
            $discount = floatval($item->discount ?? 0);

            $final = $price * (1 - $discount / 100);

            $item->final_price = round($final, 2);
        });
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }



}
