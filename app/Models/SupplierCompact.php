<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SupplierCompact extends Model
{
    
    /**
     *  @OA\Schema(
     *      schema="GetSupplierCompact",
     *      allOf={
     *          @OA\Schema(
     *              @OA\Property(property="id", type="integer", description="Record id", example="1"),
     *              @OA\Property(property="name", type="string", description="Business name", example= "Saysweet"),
     *              @OA\Property(property="address", type="string", description="Business address", example="Sayati, Kec. Margahayu, Bandung, Jawa Barat 40228"),
     *          ),
     *      },
     * ),
     */
    protected $table = 'supplier';

    protected $hidden = [
        'created_at', 
        'created_by', 
        'updated_at', 
        'updated_by', 
        'phone_number',
        'email',
        'is_deleted',
        'business_id',
    ];
}