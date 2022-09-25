<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class like extends Model
{
    use HasFactory;
    protected $table = 'likes';
    protected $primaryKey = 'id';
   
    protected $fillable = [
        'like', 'user_id', 'review_id'
    ];

    protected $hidden =[
        'created_at',
        'updated_at',
    ];

    public function user(){
        return $this->belongsTo(User::class , 'user_id');
    }

    public function review(){
        return $this->belongsTo(review::class , 'review_id');
    }
}
