<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class OauthRefreshToken extends Model
{
    
    /**
     * Remove comment to use variable bellow
     *
     * @var String
     */
    // protected $table = 'user_confirmation';
    // protected $connection = 'mysql';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "id", "access_token_id", "revoked", "expires_at"
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        ///Write hidden table field here
    ];
}