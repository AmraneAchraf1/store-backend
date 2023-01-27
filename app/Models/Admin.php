<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User;
class Admin extends User
{



    use HasApiTokens, HasFactory, Notifiable;



    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "first_name",
        "last_name",
        "email",
        "phone",
        "password",
    ];


    protected $hidden = [
        "password",
        "remember_token",
    ];
}
