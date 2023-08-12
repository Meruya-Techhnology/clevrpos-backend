<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class RegionalDistrict extends Model
{
    
    /**
     *  @OA\Schema(
     *      schema="GetDistrict",
     *      allOf={
     *          @OA\Schema(
     *              @OA\Property(property="id", type="integer", description="District id", example=117),
     *              @OA\Property(property="city_id", type="integer", description="City id", example=13),
     *              @OA\Property(property="district", type="String", description="District name", example="Batununggal"),
     *          ),
     *      },
     * ),
     */
    protected $table = 'regional_districts';
    protected $fillable = [
        'id' ,
        'city_id',
        'district', 
    ];

    protected $hidden = [
        ///Write hidden table field here
    ];

    public function city()
    {
        return $this->belongsTo('App\Models\RegionalCity', 'city_id', 'id');
    }
}