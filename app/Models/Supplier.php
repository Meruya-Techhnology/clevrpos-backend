<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    /**
     *  @OA\Schema(
     *      schema="GetSupplier",
     *      allOf={
     *          @OA\Schema(
     *              @OA\Property(property="id", type="integer", description="Record id", example="1"),
     *              @OA\Property(property="name", type="string", description="Business name", example= "Saysweet"),
     *              @OA\Property(property="address", type="string", description="Business address", example="Sayati, Kec. Margahayu, Bandung, Jawa Barat 40228"),
     *              @OA\Property(property="phone_number", type="string", description="Business phone number (HQ)", example="6282115100216"),
     *              @OA\Property(property="email", type="string", description="Business e-mail (HQ)", example="admin@saysweet.com"),
     *              @OA\Property(property="is_deleted", type="boolean", description="Verified or not flag", example=false),
     *          ),
     *      },
     * ),
     */
    protected $table = 'supplier';
    protected $fillable = [
        'id' ,
        'created_at', 
        'created_by', 
        'updated_at', 
        'updated_by', 
        'name', 
        'address', 
        'phone_number',
        'email',
        'is_deleted',
        'business_id',
    ];

    protected $hidden = [
        'created_at', 
        'created_by', 
        'updated_at', 
        'updated_by', 
        'business_id',
        'is_deleted',
    ];
}