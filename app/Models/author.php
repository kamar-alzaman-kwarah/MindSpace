<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class author extends Model
{
    use HasFactory;
    protected $table = 'authors';
    protected $primaryKey = 'id';

    protected $fillable = [
        'first_name', 'last_name', 'bio' ,'photo', 'birth', 'death'
    ];

    protected $hidden =[
        'created_at',
        'updated_at',
    ];

    public function book_authors(){
        return $this->hasMany(books_author::class , 'book_author_id');
    }

    public function favorites(){
        return $this->hasMany(favorite::class , 'author_id');
    }




}
