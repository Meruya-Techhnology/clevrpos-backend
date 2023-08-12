<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use Dusterio\LumenPassport\LumenPassport;
use Laravel\Passport\HasApiTokens;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory, HasApiTokens, Authenticatable;

    /**
     *  @OA\Schema(
     *      schema="User",
     *      allOf={
     *          @OA\Schema(
     *              @OA\Property(property="created_at", type="string", description="Created timestamp", example="2021-01-29T21:20:06.000000Z"),
     *              @OA\Property(property="created_by", type="integer", description="Data creator id", example=1),
     *              @OA\Property(property="updated_at", type="string", description="Last update timestamp", example="2021-01-29T21:20:06.000000Z"),
     *              @OA\Property(property="updated_by", type="integer", description="Last data updater", example=1),
     *              @OA\Property(property="email", type="string", description="User e-mail", example="dwikurnianto.mulyadien@gmail.com"),
     *              @OA\Property(property="phone_number", type="string", description="User phone number", example= "082115100216"),
     *              @OA\Property(property="name", type="string", description="User phone number", example= "Dwi Kurnianto Mulyadien"),
     *              @OA\Property(property="storage_id", type="string", description="User storage id", example="4fNpsssbysClV3RvAYszRwS0LTtt7kbxLHooAC1SwZ3QMkOXMijGYPnCFxij"),
     *          ),
     *      },
     * ),
     */
    protected $fillable = [
        'id',
        'name', 
        'email', 
        'password', 
        'phone_number', 
        'storage_id', 
        'access_key',
        'is_verified',
        'is_active',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    
}
