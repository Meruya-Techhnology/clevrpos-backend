<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class OauthAccessToken extends Model
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
        "id", "user_id", "client_id", "name", "scopes", "revoked", "created_at", "updated_at", "expires_at"
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        ///Write hidden table field here
    ];

    
    public function OauthRefreshToken()
    {
        return $this->hasOne('App\Models\OauthRefreshToken', 'id', 'access_token_id')->orderBy('expires_at', 'desc')->latest();
    }
}