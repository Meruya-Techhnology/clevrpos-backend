<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class RegionalSubDistrict extends Model
{
    
    
    /**
     *  @OA\Schema(
     *      schema="GetSubDistrict",
     *      allOf={
     *          @OA\Schema(
     *              @OA\Property(property="id", type="integer", description="Sub district id", example=4901),
     *              @OA\Property(property="district_id", type="integer", description="District id", example=183),
     *              @OA\Property(property="sub_district", type="String", description="Province name", example="Neglasari"),
     *              @OA\Property(property="postal_code", type="integer", description="Postal code", example=40123),
     *          ),
     *      },
     * ),
     */
    protected $table = 'regional_sub_districts';
    protected $fillable = [
        'id' ,
        'district_id',
        'sub_district', 
        'postal_code', 
    ];

    protected $hidden = [
        ///Write hidden table field here
    ];

    public function district()
    {
        return $this->belongsTo('App\Models\RegionalDistrict', 'district_id', 'id');
    }
}