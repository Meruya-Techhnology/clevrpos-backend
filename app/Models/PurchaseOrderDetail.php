<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderDetail extends Model
{
    
    /**
     *  @OA\Schema(
     *      schema="GetPurchaseOrderDetail",
     *      allOf={
     *          @OA\Schema(
     *              @OA\Property(property="id", type="integer", description="Record id", example="1"),
     *              @OA\Property(property="quantity", type="integer", description="Quantity", example=10),
     *              @OA\Property(property="item_detail", type="object", description="Item detail", ref="#/components/schemas/GetItemDetailReverse"),
     *          ),
     *      },
     * ),
     */
    
    /**
     *  @OA\Schema(
     *      schema="CreatePurchaseOrderDetail",
     *      allOf={
     *          @OA\Schema(
     *              @OA\Property(property="purchase_order_id", type="integer", description="Purchase order id", example=1),
     *              @OA\Property(property="item_detail_id", type="integer", description="Item id", example=1),
     *              @OA\Property(property="quantity", type="integer", description="Quantity", example=10),
     *          ),
     *      },
     * ),
     */
    protected $table = 'purchase_order_detail';
    protected $fillable = [
        'id' ,
        'created_at', 
        'created_by', 
        'updated_at', 
        'updated_by', 
        'purchase_order_id', 
        'item_detail_id', 
        'quantity', 
    ];

    protected $hidden = [
        'created_at', 
        'created_by', 
        'updated_at', 
        'updated_by', 
        'purchase_order_id', 
        'item_detail_id', 
    ];

    public function itemDetail()
    {
        return $this->belongsTo('App\Models\ItemDetail', 'item_detail_id', 'id');
    }
}