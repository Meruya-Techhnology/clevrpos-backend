<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Regional extends Model
{
    /**
     *  @OA\Schema(
     *      schema="GetRegional",
     *      allOf={
     *          @OA\Schema(
     *              @OA\Property(property="postal_code", type="integer", description="Data creator id", example=40228),
     *              @OA\Property(property="sub_district_id", type="integer", description="Sub district id", example=51325),
     *              @OA\Property(property="sub_district", type="string", description="Sub district", example="Margahayu"),
     *              @OA\Property(property="district_id", type="integer", description="Last update timestamp", example=206),
     *              @OA\Property(property="district", type="string", description="Last data updater", example="Margahayu"),
     *              @OA\Property(property="city_id", type="integer", description="Last update timestamp", example=447),
     *              @OA\Property(property="city", type="string", description="Last data updater", example="Bandung, Kab."),
     *              @OA\Property(property="province_id", type="integer", description="Last update timestamp", example=3),
     *              @OA\Property(property="province", type="string", description="Last data updater", example="Jawa Barat"),
     *              @OA\Property(property="name", type="string", description="Regional name full", example="Sayati, Margahayu, Bandung, Kab., Jawa Barat"),
     *          ),
     *      },
     * ),
     */
    protected $table = 'regionals';
    protected $fillable = [
        'postal_code' ,
        'sub_district_id', 
        'sub_district', 
        'district', 
        'district_id', 
        'city',
        'city_id', 
        'province',
        'province_id', 
        'name', 
    ];

    protected $hidden = [
        ///Write hidden table field here
    ];
}