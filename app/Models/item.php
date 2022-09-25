<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class item extends Model
{
    use HasFactory;
    protected $table = 'items';
    protected $primaryKey = 'id';
   
    protected $fillable = [
         'book_id','cart_id','quantity'
    ];

    protected $hidden =[
        'created_at',
        'updated_at',
    ];

    public function cart(){
        return $this->belongsTo(cart::class , 'cart_id');
    }

    public function book(){
        return $this->belongsTo(book::class , 'book_id');
    }

    public function bill_items(){
        return $this->hasMany(bill_item::class , 'bill_item_id');
    }
}
