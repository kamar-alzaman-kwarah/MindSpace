<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class category_book extends Model
{
    use HasFactory;
    protected $table = 'category_books';
    protected $primaryKey = 'id';
   
    protected $fillable = [
         'category_id', 'book_id'
    ];

    protected $hidden =[
        'created_at',
        'updated_at',
    ];

    public function category(){
        return $this->belongsTo(category::class , 'category_id');
    }

    public function book(){
        return $this->belongsTo(book::class , 'book_id');
    }

    public function books(){
        return $this->hasMany(book::class);
    }
}
