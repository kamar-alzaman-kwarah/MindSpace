<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class category_user extends Model
{
    use HasFactory;
    protected $table = 'category_users';
    protected $primaryKey = 'id';
   
    protected $fillable = [
         'category_id', 'user_id'
    ];

    protected $hidden =[
        'created_at',
        'updated_at',
    ];

    public function category(){
        return $this->belongsTo(category::class , 'category_id');
    }

    public function user(){
        return $this->belongsTo(User::class , 'user_id');
    }
}
