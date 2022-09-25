<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class category extends Model
{
    use HasFactory;
    protected $table = 'categories';
    protected $primaryKey = 'id';
   
    protected $fillable = [
        'name',	'image'
    ];

    protected $hidden =[
        'created_at',
        'updated_at',
    ];

    public function books(){
        return $this->hasMany(book::class);
    }

    public function users(){
        return $this->hasMany(User::class , 'user_id');
    }
}
