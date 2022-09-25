<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class role extends Model
{
    use HasFactory;
    protected $table = 'roles';
    protected $primaryKey = 'id';
   
    protected $fillable = [
        'role_name' 
    ];

    protected $hidden =[
        'created_at',
        'updated_at',
    ];

    public function users(){
        return $this->hasMany(User::class , 'user_id');
    }
}
