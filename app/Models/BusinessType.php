<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class BusinessType extends Model
{
    /**
     *  @OA\Schema(
     *      schema="GetBusinessType",
     *      allOf={
     *          @OA\Schema(
     *              @OA\Property(property="id", type="integer", description="Record id", example="1"),
     *              @OA\Property(property="name", type="string", description="Business name", example= "Bakery & Pastry"),
     *          ),
     *      },
     * ),
     */
    protected $table = 'business_type';
    protected $fillable = [
        'id' ,
        'created_at', 
        'created_by', 
        'updated_at', 
        'updated_by', 
        'name', 
    ];

    protected $hidden = [
        'created_at', 
        'created_by', 
        'updated_at', 
        'updated_by', 
    ];
}