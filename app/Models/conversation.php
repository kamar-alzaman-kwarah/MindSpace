<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class conversation extends Model
{
    use HasFactory;
    protected $table = 'conversations';
    protected $primaryKey = 'id';
   
    protected $fillable = [
         'user_id','parent_id','wall_id','message'
    ];

    protected $hidden =[
        'created_at',
        'updated_at',
    ];

    //public $withCount = ['conversations'];

    public function wall(){
        return $this->belongsTo(wall::class , 'wall_id');
    }

    public function user(){
        return $this->belongsTo(User::class , 'user_id');
    }

    public function conversation(){
        return $this->belongsTo(conversation::class , 'conversation_id');
    }

    public function conversations(){
        return $this->hasMany(conversation::class , 'parent_id');
    }
}
