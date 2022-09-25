<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class playlist_book extends Model
{
    use HasFactory;
    protected $table = 'playlist_books';
    protected $primaryKey = 'id';

    protected $fillable = [
        'book_id', 'playlist_id'
   ];

   protected $hidden =[
       'created_at',
       'updated_at',
   ];

    public function book(){
        return $this->belongsTo(book::class , 'book_id');
    }

    public function playlist(){
        return $this->belongsTo(playlist::class , 'playlist_id');
    }
}
