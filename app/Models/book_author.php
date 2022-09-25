<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class book_author extends Model
{
    use HasFactory;
    protected $table = 'book_authors';
    protected $primaryKey = 'id';
   
    protected $fillable = [
        'book_id',	'author_id'
    ];

    protected $hidden =[
        'created_at',
        'updated_at',
    ];

    public function book(){
        return $this->belongsTo(book::class , 'book_id');
    }

    public function author(){
        return $this->belongsTo(author::class , 'author_id');
    }

}
