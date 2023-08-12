<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class RegionalCity extends Model
{
    
    /**
     *  @OA\Schema(
     *      schema="GetCity",
     *      allOf={
     *          @OA\Schema(
     *              @OA\Property(property="id", type="integer", description="Created timestamp", example=1),
     *              @OA\Property(property="province_id", type="integer", description="Created timestamp", example=1),
     *              @OA\Property(property="city", type="string", description="Data creator id", example="Kota Bandung"),
     *              @OA\Property(property="code", type="string", description="Last update timestamp", example="BDG"),
     *          ),
     *      },
     * ),
     */
    protected $table = 'regional_cities';
    protected $fillable = [
        'id' ,
        'province_id', 
        'city', 
        'code', 
    ];

    protected $hidden = [
        ///Write hidden table field here
    ];
    
    public function province()
    {
        return $this->belongsTo('App\Models\RegionalProvince', 'province_id', 'id');
    }
}