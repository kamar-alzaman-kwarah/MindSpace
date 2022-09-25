<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class spoiler extends Model
{
    use HasFactory;
    protected $table = 'spoilers';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id', 'review_id'
    ];

    protected $hidden =[
        'created_at',
        'updated_at',
    ];
}
