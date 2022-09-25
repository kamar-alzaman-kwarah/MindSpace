<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class playlist extends Model
{
    use HasFactory;
    protected $table = 'playlists';
    protected $primaryKey = 'id';
   
    protected $fillable = [
        'user_id', 'name' , 'state'
    ];

    protected $hidden =[
        'created_at',
        'updated_at',
    ];

    public $withCount = ['playlist_books'];

    public function user(){
        return $this->belongsTo(User::class , 'user_id');
    }

    public function playlist_books(){
        return $this->hasMany(playlist_book::class);
    }
}
