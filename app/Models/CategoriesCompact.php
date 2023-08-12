<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CategoriesCompact extends Model
{
    /**
     *  @OA\Schema(
     *      schema="GetCategoryCompact",
     *      allOf={
     *          @OA\Schema(
     *              @OA\Property(property="id", type="integer", description="Record id", example="1"),
     *              @OA\Property(property="title", type="string", description="Category title", example="Dessert cup"),
     *          ),
     *      },
     * ),
     */
    protected $table = 'categories';
    protected $fillable = [
        'id' ,
        'created_at', 
        'created_by', 
        'updated_at', 
        'updated_by', 
        'business_id', 
        'title', 
        'is_deleted', 
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