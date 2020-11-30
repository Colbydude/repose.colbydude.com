<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SecretSanta2020 extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'secret_santa_2020';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'address',
        'message',
    ];
}
