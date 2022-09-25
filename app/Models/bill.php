<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bill extends Model
{
    use HasFactory;
    protected $table = 'bills';
    protected $primaryKey = 'id';

    protected $fillable = [
        'cart_id','state','user_id','address_id','phone_number','created_at',
    ];

    protected $hidden =[
        'updated_at',
    ];

    public function cart(){
        return $this->belongsTo(cart::class , 'cart_id');
    }

    public function shipper(){
        return $this->belongsTo(user::class , 'user_id');
    }

    public function address(){
        return $this->belongsTo(address::class , 'address_id');
    }

    public function bill_items(){
        return $this->hasMany(bill_item::class);
    }

}
