<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bill_item extends Model
{
    use HasFactory;
    protected $table = 'bill_items';
    protected $primaryKey = 'id';

    protected $fillable = [
         'price', 'bill_id', 'item_id' , 'donate_cart_id'
    ];

    protected $hidden =[
        'created_at',
        'updated_at',
    ];

    public function item(){
        return $this->belongsTo(item::class , 'item_id');
    }

    public function bill(){
        return $this->belongsTo(bill::class , 'bill_id');
    }

    public function donate_cart(){
        return $this->belongsTo(donate_cart::class , 'donate_cart_id');
    }

}
