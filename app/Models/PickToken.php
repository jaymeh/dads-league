<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Password;


class PickToken extends Model
{
    public static function boot() {
        static::creating(function($model) {
            $token = Password::getRepository()->createNewToken();
            $model->token = $token;
        });
    }
}
