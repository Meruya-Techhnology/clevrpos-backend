<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UserConfirmation extends Model
{
    protected $table = 'user_confirmation';

    protected $fillable = [
        'id' ,'created_by', 'created_at', 'user_id', 'access_key', 'is_revoked', 'is_used', 'used_at', 'expired_at', 'confirmation_type', 
        'retry_count'
    ];
}