<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class donate extends Model
{
    use HasFactory;
    protected $table = 'donates';
    protected $primaryKey = 'id';
   
    protected $fillable = [
         'user_id','phone_number','name'
    ];

    protected $hidden =[
        'created_at',
        'updated_at',
    ];

    public function user(){
        return $this->belongsTo(User::class , 'user_id');
    }

    public function book_donates(){
        return $this->hasMany(book_donate::class , 'donate_id');
    }

    public function donate_admins(){
        return $this->hasMany(donate_admin::class , 'donate_admin_id');
    }
}
