<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'user_id',
        'phone',
        'address',
        'age',
        'gender',
    ];
}
