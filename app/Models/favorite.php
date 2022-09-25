<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class favorite extends Model
{
    use HasFactory;
    protected $table = 'favorites';
    protected $primaryKey = 'id';
   
    protected $fillable = [
         'author_id','user_id'
    ];

    protected $hidden =[
        'created_at',
        'updated_at',
    ];

    public function author(){
        return $this->belongsTo(author::class , 'author_id');
    }

    public function user(){
        return $this->belongsTo(User::class , 'user_id');
    }
}
