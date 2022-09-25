<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class book extends Model
{
    use HasFactory;
    protected $table = 'books';
    protected $primaryKey = 'id';
   
    protected $fillable = [
         'name', 'description'	,'page_number'	,'publishing_house'	,'publishing_year',
         	'copies_number'	,'price',	'cover',	'classification',	'state',	'PDF',	'audio_book','amateur'
    ];

    protected $hidden =[
        'created_at',
        'updated_at',
    ];

    public function category_books(){
        return $this->hasMany(category_book::class , 'category_book_id');
    }

    public function author_books(){
        return $this->hasMany(author_book::class , 'author_book_id');
    }

    public function discounts(){
        return $this->hasMany(discount::class , 'discount_id');
    }

    public function playlists(){
        return $this->hasMany(playlist::class , 'playlist_id');
    }

    public function rates(){
        return $this->hasMany(rate::class);
    }

    public function reserveds(){
        return $this->hasMany(reserved::class , 'reserved_id');
    }

    public function reviews(){
        return $this->hasMany(review::class , 'review_id');
    }

    public function items(){
        return $this->hasMany(item::class , 'item_id');
    }

}
