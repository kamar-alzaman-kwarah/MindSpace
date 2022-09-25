<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class amateure_writer extends Model
{
    use HasFactory;
    protected $table = 'amateure_writers';
    protected $primaryKey = 'id';

    protected $fillable = [
         'name', 'description',	'PDF', 'phone_number', 'user_id'
    ];

    protected $hidden =[
        'created_at',
        'updated_at',
    ];

    public function user(){
        return $this->belongsTo(User::class , 'user_id');
    }

    public function amateure_admins(){
        return $this->belongsTo(amateure_admin::class , 'amateure_admin_id');
    }


}
