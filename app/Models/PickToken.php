<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Password;


class PickToken extends Model
{
	protected $dates = [
		'expiry'
	];

    public static function boot() {
        static::creating(function($model) {
            $token = Password::getRepository()->createNewToken();
            $model->token = $token;
            $model->active = 1;
        });
    }

    public function player()
    {
    	return $this->belongsTo(Player::class);
    }
}
