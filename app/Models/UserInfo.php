<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'phone',
        'address',
        'age',
        'gender',
    ];

public function user()
{
    return $this->belongsTo(User::class);
}
public function getFullNameAttribute(){
    return trim("{$this->first_name} {$this->middle_name} {$this->last_name}");
}
public function info()
{
    return $this->hasOne(UserInfo::class, 'user_id');
}

}
