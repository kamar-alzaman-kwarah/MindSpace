<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class donate_admin extends Model
{
    use HasFactory;
    protected $table = 'donate_admins';
    protected $primaryKey = 'id';
   
    protected $fillable = [
        'donate_id','user_id'
    ];

    protected $hidden =[
        'created_at',
        'updated_at',
    ];

    public function user(){
        return $this->belongsTo(User::class , 'user_id');
    }

    public function donate(){
        return $this->belongsTo(donate::class , 'donate_id');
    }

}
