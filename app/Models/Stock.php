<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    
    /**
     *  @OA\Schema(
     *      schema="GetStock",
     *      allOf={
     *          @OA\Schema(
     *              @OA\Property(property="id", type="integer", description="Record id", example="1"),
     *              @OA\Property(property="created_at", type="string", description="Created timestamp", example="2021-01-29T21:20:06.000000Z"),
     *              @OA\Property(property="quantity", type="integer", description="Stock quantity", example=100),
     *              @OA\Property(property="item_detail", type="object", description="Item detail", ref="#/components/schemas/GetItemDetail"),
     *              @OA\Property(property="outlet", type="object", description="Puchase order detail", ref="#/components/schemas/GetOutletCompact"),
     *          ),
     *      },
     * ),
     */
    /**
     *  @OA\Schema(
     *      schema="GetStockCompact",
     *      allOf={
     *          @OA\Schema(
     *              @OA\Property(property="id", type="integer", description="Record id", example="1"),
     *              @OA\Property(property="quantity", type="integer", description="Stock quantity", example=100),
     *          ),
     *      },
     * ),
     */
    protected $table = 'stocks';
    protected $fillable = [
        'id' ,
        'created_at', 
        'created_by', 
        'updated_at', 
        'updated_by', 
        'business_id', 
        'outlet_id', 
        'item_detail_id', 
        'quantity', 
    ];

    protected $hidden = [
        'created_at', 
        'created_by', 
        'updated_at', 
        'updated_by', 
        'business_id',
        'outlet_id', 
        'item_detail_id', 
    ];

    public function outlet()
    {
        return $this->belongsTo('App\Models\OutletCompact', 'outlet_id', 'id');
    }
    
    public function itemDetail()
    {
        return $this->belongsTo('App\Models\ItemDetail', 'item_detail_id', 'id');
    }
}