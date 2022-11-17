<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class SecretSanta2022 extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'secret_santa_2022';

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

    /**
     * Get the secret santa record associated with match.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function match()
    {
        return $this->belongsTo(SecretSanta2022::class, 'match_id');
    }

    /**
     * Get the user record associated with the secret santa.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
