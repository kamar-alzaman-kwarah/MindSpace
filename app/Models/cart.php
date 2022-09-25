<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cart extends Model
{
    use HasFactory;
    protected $table = 'carts';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
    ];

    protected $hidden =[
        'created_at',
        'updated_at',
    ];

    public function items(){
        return $this->hasMany(item::class);
    }

    public function donate_carts(){
        return $this->hasMany(donate_cart::class);
    }

    public function user(){
        return $this->belongsTo(User::class , 'user_id');
    }

}
