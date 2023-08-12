<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Outlet extends Model
{
    
    /**
     *  @OA\Schema(
     *      schema="GetOutlet",
     *      allOf={
     *          @OA\Schema(
     *              @OA\Property(property="id", type="integer", description="Outlet id", example=1),
     *              @OA\Property(property="name", type="string", description="Outlet name", example= "Saysweet kopo"),
     *              @OA\Property(property="code", type="integer", description="Outlet code, used for standarized code", example="SSK"),
     *              @OA\Property(property="address", type="string", description="Outlet address", example="Sayati, Kec. Margahayu, Bandung, Jawa Barat 40228"),
     *              @OA\Property(property="latitude", type="number", description="Latitude coordinate", example=-6.9754222),
     *              @OA\Property(property="longitude", type="number", description="Longitude coordinate", example=107.577356),
     *              @OA\Property(property="business_id", type="integer", description="Business id", example=1),
     *              @OA\Property(property="phone_number", type="string", description="User phone number", example="082115100216"),
     *              @OA\Property(property="postal_code", type="integer", description="Outlet postal code", example=40376),
     *          ),
     *          @OA\Schema(
     *              @OA\Property(property="regional", type="array", description="Regional data",
     *                  @OA\Items(ref="#/components/schemas/GetRegional"),
     *              ),
     *          ),
     *      },
     * ),
     */
    protected $table = 'outlets';
    protected $fillable = [
        'id' ,
        'created_by', 
        'created_at', 
        'updated_at', 
        'updated_by', 
        'name', 
        'code', 
        'address', 
        'latitude', 
        'longitude', 
        'business_id', 
        'sub_district_id', 
        'phone_number',
        'is_deleted',
        'postal_code',
    ];

    protected $hidden = [
        'created_by', 
        'created_at', 
        'updated_at', 
        'updated_by', 
        'sub_district_id', 
        'is_deleted',
    ];

    public function regional()
    {
        return $this->belongsTo('App\Models\Regional', 'sub_district_id', 'sub_district_id');
    }
}