<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rate extends Model
{
    use HasFactory;
    protected $table = 'rates';
    protected $primaryKey = 'id';
   
    protected $fillable = [
         'book_id', 'user_id', 'stars_number'
    ];

    protected $hidden =[
        'created_at',
        'updated_at',
    ];

    public function book(){
        return $this->belongsTo(book::class , 'book_id');
    }

    public function user(){
        return $this->belongsTo(User::class , 'user_id');
    }


    

}
