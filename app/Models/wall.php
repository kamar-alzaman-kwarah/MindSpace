<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class wall extends Model
{
    use HasFactory;
    protected $table = 'walls';
    protected $primaryKey = 'id';
   
    protected $fillable = [
         'user_id'
    ];

    protected $hidden =[
        'created_at',
        'updated_at',
    ];

    public function user(){
        return $this->belongsTo(User::class , 'user_id');
    }

    public function conversations(){
        return $this->hasMany(conversation::class );
    }

}
