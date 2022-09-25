<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class address extends Model
{
    use HasFactory;
    protected $table = 'addresses';
    protected $primaryKey = 'id';
   
    protected $fillable = [
        'country' , 'state','city','street'
    ];

    protected $hidden =[
        'created_at',
        'updated_at',
    ];

    public function users(){
        return $this->hasMany(User::class , 'user_id');
    }
}
