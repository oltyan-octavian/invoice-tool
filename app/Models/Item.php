<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'name',
        'price_without_tax',
        'tax',
        'discount',
        'final_price'
    ];

    protected static function booted()
    {
        static::creating(function ($item) {
            $price = floatval($item->price_without_tax ?? 0);
            $tax = floatval($item->tax ?? 0);
            $discount = floatval($item->discount ?? 0);

            $priceWithTax = $price + ($price * $tax / 100);
            $final = $priceWithTax * (1 - $discount / 100);

            $item->final_price = round($final, 2);
        });
    }




}
