<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class res extends Model
{
    use HasFactory;
    protected $table = 'res';
    protected $primaryKey = 'id';
   
    protected $fillable = [
         'book_id', 'user_id','number'
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
