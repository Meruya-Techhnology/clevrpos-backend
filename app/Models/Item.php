<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    /**
     *  @OA\Schema(
     *      schema="GetItem",
     *      allOf={
     *          @OA\Schema(
     *              @OA\Property(property="id", type="integer", description="Record id", example="1"),
     *              @OA\Property(property="name", type="string", description="Item name", example="Banoffee"),
     *              @OA\Property(property="category_id", type="integer", description="Item category id", example=1),
     *              @OA\Property(property="created_at", type="string", description="Created timestamp", example="2021-01-29T21:20:06.000000Z"),
     *              @OA\Property(property="name", type="string", description="Item name", example="Banoffee"),
     *              @OA\Property(property="tag", type="string", description="Item tag", example="#banoffee"),
     *              @OA\Property(property="item_detail", type="array", description="Item details",
     *                  @OA\Items(ref="#/components/schemas/GetItemDetail"),
     *              ),
     *              @OA\Property(property="category", type="object", description="Item details", ref="#/components/schemas/GetCategoryCompact"),
     *          ),
     *      },
     * ),
     */
    /**
     *  @OA\Schema(
     *      schema="GetItemAsParrent",
     *      allOf={
     *          @OA\Schema(
     *              @OA\Property(property="id", type="integer", description="Record id", example="1"),
     *              @OA\Property(property="name", type="string", description="Item name", example="Banoffee"),
     *              @OA\Property(property="tag", type="string", description="Item tag", example="#banoffee"),
     *          ),
     *      },
     * ),
     */
    protected $table = 'items';
    protected $fillable = [
        'id' ,
        'created_by', 
        'updated_at', 
        'updated_by', 
        'business_id', 
        'name', 
        'category_id', 
        'tag', 
        'is_deleted'
    ];

    protected $hidden = [
        'created_by', 
        'updated_at', 
        'updated_by', 
        'business_id',
        'category_id', 
        'is_deleted',
    ];

    public function itemDetail()
    {
        return $this->hasMany('App\Models\ItemDetail', 'item_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\CategoriesCompact', 'category_id', 'id');
    }
}