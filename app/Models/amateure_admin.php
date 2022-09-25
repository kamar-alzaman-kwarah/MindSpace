<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class amateure_admin extends Model
{
    use HasFactory;
    protected $table = 'amateure_admins';
    protected $primaryKey = 'id';
   
    protected $fillable = [
         'amateure_id', 'user_id'
    ];

    protected $hidden =[
        'created_at',
        'updated_at',
    ];

    public function user(){
        return $this->belongsTo(User::class , 'user_id');
    }

    public function amateure_writer(){
        return $this->belongsTo(amateure_writer::class , 'amateure_id');
    }

    
}
