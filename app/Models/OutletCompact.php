<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class OutletCompact extends Model
{
    
    /**
     *  @OA\Schema(
     *      schema="GetOutletCompact",
     *      allOf={
     *          @OA\Schema(
     *              @OA\Property(property="id", type="integer", description="Outlet id", example=1),
     *              @OA\Property(property="name", type="string", description="Outlet name", example= "Saysweet kopo"),
     *              @OA\Property(property="code", type="integer", description="Outlet code, used for standarized code", example="SSK"),
     *              @OA\Property(property="address", type="string", description="Outlet address", example="Sayati, Kec. Margahayu, Bandung, Jawa Barat 40228"),
     *          ),
     *      },
     * ),
     */
    protected $table = 'outlets';

    protected $hidden = [
        'created_by', 
        'created_at', 
        'updated_at', 
        'updated_by', 
        'latitude', 
        'longitude', 
        'business_id', 
        'sub_district_id', 
        'phone_number',
        'is_deleted',
        'postal_code',
    ];

}