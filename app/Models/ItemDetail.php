<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ItemDetail extends Model
{
    /**
     *  @OA\Schema(
     *      schema="GetItemDetail",
     *      allOf={
     *          @OA\Schema(
     *              @OA\Property(property="id", type="integer", description="Record id", example="1"),
     *              @OA\Property(property="variant", type="string", description="Variant name", example="Topping tiramisu"),
     *              @OA\Property(property="sell_price", type="integer", description="Item sell price", example=10000),
     *              @OA\Property(property="buy_price", type="integer", description="Item buy price", example=10000),
     *              @OA\Property(property="sku", type="string", description="Item SKU", example="BNF04192021DB"),
     *              @OA\Property(property="image_url", type="string", description="Item image", example="https://lh5.googleusercontent.com/p/AF1QipPBsvuGf1298eauB-01hzB96dplSHz0c-HquS0q=w408-h408-k-no"),
     *              @OA\Property(property="is_tracked", type="boolean", description="Tracking flag", example=true),
     *              @OA\Property(property="lower_limit", type="integer", description="Minimal quantity for reminder", example=10),
     *              @OA\Property(property="stock", type="object", description="Item", ref="#/components/schemas/GetStockCompact"),
     *          ),
     *      },
     * ),
     */

    /**
     *  @OA\Schema(
     *      schema="GetItemDetailReverse",
     *      allOf={
     *          @OA\Schema(
     *              @OA\Property(property="id", type="integer", description="Record id", example="1"),
     *              @OA\Property(property="variant", type="string", description="Variant name", example="Topping tiramisu"),
     *              @OA\Property(property="sell_price", type="integer", description="Item sell price", example=10000),
     *              @OA\Property(property="buy_price", type="integer", description="Item buy price", example=10000),
     *              @OA\Property(property="sku", type="string", description="Item SKU", example="BNF04192021DB"),
     *              @OA\Property(property="image_url", type="string", description="Item image", example="https://lh5.googleusercontent.com/p/AF1QipPBsvuGf1298eauB-01hzB96dplSHz0c-HquS0q=w408-h408-k-no"),
     *              @OA\Property(property="is_tracked", type="boolean", description="Tracking flag", example=true),
     *              @OA\Property(property="lower_limit", type="integer", description="Minimal quantity for reminder", example=10),
     *              @OA\Property(property="item", type="object", description="Item", ref="#/components/schemas/GetItemAsParrent"),
     *          ),
     *      },
     * ),
     */

    /**
     *  @OA\Schema(
     *      schema="CreateItemDetail",
     *      allOf={
     *          @OA\Schema(
     *              @OA\Property(property="variant", type="string", description="Variant name", example="Topping tiramisu"),
     *              @OA\Property(property="sell_price", type="integer", description="Item sell price", example=10000),
     *              @OA\Property(property="buy_price", type="integer", description="Item buy price", example=10000),
     *              @OA\Property(property="sku", type="string", description="Item SKU", example="BNF04192021DB"),
     *              @OA\Property(property="image_url", type="string", description="Item image", example="https://lh5.googleusercontent.com/p/AF1QipPBsvuGf1298eauB-01hzB96dplSHz0c-HquS0q=w408-h408-k-no"),
     *              @OA\Property(property="is_tracked", type="boolean", description="Tracking flag", example=true),
     *              @OA\Property(property="lower_limit", type="integer", description="Minimal quantity for reminder", example=10),
     *          ),
     *      },
     * ),
     */
    protected $table = 'item_detail';
    protected $fillable = [
        'id' ,
        'created_at', 
        'created_by', 
        'updated_at', 
        'updated_by', 
        'item_id', 
        'variant', 
        'sell_price', 
        'buy_price',
        'sku',
        'image_url', 
        'is_tracked',
        'lower_limit', 
    ];

    protected $hidden = [
        'created_at', 
        'created_by', 
        'updated_at', 
        'updated_by', 
        'item_id', 
    ];

    public function item()
    {
        return $this->belongsTo('App\Models\Item', 'item_id', 'id');
    }

    public function stock()
    {
        return $this->belongsTo('App\Models\Stock', 'id', 'item_detail_id');
    }
}