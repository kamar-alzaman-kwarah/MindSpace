<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class book_donate extends Model
{
    use HasFactory;
    protected $table = 'book_donates';
    protected $primaryKey = 'id';

    protected $fillable = [
         'name', 'photo', 'donate_id','acceptance'
    ];

    protected $hidden =[
        'created_at',
        'updated_at',
    ];

    public function donate(){
        return $this->belongsto(donate::class , 'donate_id');
    }

    public function book_donate(){
        return $this->belongsTo(donate_cart::class , 'donate_cart_id');
    }
}
