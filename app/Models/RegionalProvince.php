<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class RegionalProvince extends Model
{
    
    /**
     *  @OA\Schema(
     *      schema="GetProvince",
     *      allOf={
     *          @OA\Schema(
     *              @OA\Property(property="id", type="integer", description="Province id", example=3),
     *              @OA\Property(property="province", type="String", description="Province name", example="Jawa Barat"),
     *          ),
     *      },
     * ),
     */
    protected $table = 'regional_provinces';
    protected $fillable = [
        'id' ,
        'province', 
    ];

    protected $hidden = [
        ///Write hidden table field here
    ];
}