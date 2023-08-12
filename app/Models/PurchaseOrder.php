<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    /**
     *  @OA\Schema(
     *      schema="GetPurchaseOrder",
     *      allOf={
     *          @OA\Schema(
     *              @OA\Property(property="id", type="integer", description="Record id", example="1"),
     *              @OA\Property(property="created_at", type="string", description="Created timestamp", example="2021-01-29T21:20:06.000000Z"),
     *              @OA\Property(property="order_number", type="string", description="Purchase order number", example="PO200420200001"),
     *              @OA\Property(property="purchase_order_detail", type="array", description="Puchase order detail",
     *                  @OA\Items(ref="#/components/schemas/GetPurchaseOrderDetail"),
     *              ),
     *              @OA\Property(property="supplier", type="object", description="Puchase order detail", ref="#/components/schemas/GetSupplierCompact"),
     *              @OA\Property(property="outlet", type="object", description="Puchase order detail", ref="#/components/schemas/GetOutletCompact"),
     *          ),
     *      },
     * ),
     */
    protected $table = 'purchase_order';
    protected $fillable = [
        'id' ,
        'created_at', 
        'created_by', 
        'updated_at', 
        'updated_by', 
        'order_number', 
        'business_id', 
        'outlet_id', 
        'supplier_id', 
    ];

    protected $hidden = [
        'created_by', 
        'updated_at', 
        'updated_by', 
        'business_id',
        'outlet_id', 
        'supplier_id', 
    ];

    public function purchaseOrderDetail()
    {
        return $this->hasMany('App\Models\purchaseOrderDetail', 'purchase_order_id', 'id');
    }
    public function outlet()
    {
        return $this->belongsTo('App\Models\OutletCompact', 'outlet_id', 'id');
    }
    public function supplier()
    {
        return $this->belongsTo('App\Models\SupplierCompact', 'supplier_id', 'id');
    }
}