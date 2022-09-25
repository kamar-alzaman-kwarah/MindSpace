<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class discount extends Model
{
    use HasFactory;
    protected $table = 'discounts';
    protected $primaryKey = 'id';
   
    protected $fillable = [
        'start_date',	'end_date',	'ratio',	'book_id',	'user_id'	
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
