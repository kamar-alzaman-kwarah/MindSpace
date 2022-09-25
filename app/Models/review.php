<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class review extends Model
{
    use HasFactory;
    protected $table = 'reviews';
    protected $primaryKey = 'id';

    protected $fillable = [
         'user_id','book_id','parent_id','comment','spoiler'
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

    public function reviews(){
        return $this->hasMany(review::class , 'parent_id');
    }

    public function likes(){
        return $this->hasMany(like::class , 'like_id');
    }

}
