<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'first_name' , 'last_name' , 'phone_number' , 'bio' , 'role_id' , 'address_id' , 'email'
        , 'password', 'photo' , 'activated'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function oauth_access_tokens(){
        return $this->hasMany(oauth_access_token::class );
    }

    public function address(){
        return $this->belongsTo(address::class , 'address_id');
    }

    public function role(){
        return $this->belongsTo(role::class , 'role_id');
    }

    public function wall(){
        return $this->belongsTo(wall::class , 'wall_id');
    }

    public function category_users(){
        return $this->hasMany(category_user::class );
    }

    public function favorites(){
        return $this->hasMany(favorite::class , 'user_id');
    }

    public function discounts(){
        return $this->hasMany(discount::class , 'discount_id');
    }

    public function reviews(){
        return $this->hasMany(review::class , 'review_id');
    }

    public function likes(){
        return $this->hasMany(like::class , 'like_id');
    }

    public function playlists(){
        return $this->hasMany(playlist::class , 'playlist_id');
    }

    public function rates(){
        return $this->hasMany(rate::class , 'rate_id');
    }

    public function reserveds(){
        return $this->hasMany(reserved::class , 'reserved_id');
    }

    public function conversations(){
        return $this->hasMany(conversation::class , 'conversation_id');
    }

    public function carts(){
        return $this->hasMany(cart::class , 'cart_id');
    }

    public function cart_admins(){
        return $this->hasMany(cart_admin::class , 'cart_admin_id');
    }

    public function donates(){
        return $this->hasMany(donate::class , 'donate_id');
    }

    public function donate_admins(){
        return $this->hasMany(donate_admin::class , 'donate_admin_id');
    }

    public function amateure_writers(){
        return $this->hasMany(amateure_writer::class , 'amateure_writer_id');
    }

    public function amateure_admins(){
        return $this->hasMany(amateure_admin::class , 'amateure_admin_id');
    }
}
