<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Cart extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $primaryKey = "id";
    protected $fillable = [
        "user_id",
        "user_cart_id",
        "product_id",
    ];

    protected  static  function  booted()
    {
        static::creating(function ( $cart) {
            $cart->id = Str::uuid();
        });
    }
}
