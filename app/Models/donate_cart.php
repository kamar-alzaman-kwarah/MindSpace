<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class donate_cart extends Model
{
    use HasFactory;
    protected $table = 'donate_carts';
    protected $primaryKey = 'id';

    protected $fillable = [
         'cart_id','book_donate_id','created_at',
    ];

    protected $hidden =[
        'updated_at',
    ];

    public function cart(){
        return $this->belongsTo(cart::class,'cart_id' );
    }

    public function book_donate(){
        return $this->belongsTo(book_donate::class , 'book_donate_id');
    }

    public function bill_items(){
        return $this->hasMany(bill_item::class , 'bill_item_id');
    }
}
