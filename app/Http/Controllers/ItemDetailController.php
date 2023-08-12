<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ItemDetailController extends Controller
{
    
    /**
     * @OA\Post(
     *      path="/item_detail",
     *      operationId="createItemDetail",
     *      tags={"Item Detail"},
     *      security={{"token": {}}},
     *      @OA\RequestBody(
     *          required=true,
     *          description="Items create payload",
     *          @OA\JsonContent(
     *              required={"item_id","variant", "sell_price"},
     *              @OA\Property(property="item_id", type="integer", description="Item id", example=1),
     *              @OA\Property(property="variant", type="string", description="Variant name", example="Topping mangga"),
     *              @OA\Property(property="sell_price", type="integer", description="Item sell price", example=27000),
     *              @OA\Property(property="buy_price", type="integer", description="Item buy price", example=25000),
     *              @OA\Property(property="sku", type="string", description="Item sku code", example="BFETM01"),
     *              @OA\Property(property="image_url", type="string", description="Image url", example="https://asset.kompas.com/crops/mD0DpkNbl3sOkDv_Mw4IpLcuhGA=/204x113:1467x955/750x500/data/photo/2021/02/06/601e8af59b925.jpg"),
     *              @OA\Property(property="is_tracked", type="boolean", description="Item tracking status", example=false),
     *              @OA\Property(property="lower_limit", type="int", description="Lower limit for notification", example=10),
     *          ),
     *      ),
     *      @OA\Response(
     *          response="201",
     *          description="Response description",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/CreateSuccessResponse"),
     *          )
     *      ),
     *      @OA\Response(
     *          response="400",
     *          description="Error: Bad request. When expected parameters were not supplied.",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/BadRequest"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response="401",
     *          description="Error: Unauthorized. When rest-api client isn't authorized to use this API",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/Unauthorized"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response="403",
     *      description="Error: Forbiden access. When user doesn't have permission to access this API.",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/ForbidenAccess"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response="500",
     *          description="Error: Internal server error. This is happen when server had internal error",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/InternalServerError"),
     *          ),
     *     ),
     * )
     */
    public function Create(Request $request){
        try {
            DB::beginTransaction();
            $today = Carbon::now(new \DateTimeZone('Asia/Jakarta'));
            $itemDetailId = ItemDetail::insertGetId([
                    'created_by' => auth()->user()->id,
                    'created_at' => $today,
                    'item_id' => $request->item_id,
                    'variant' => $request->variant,
                    'sell_price' => $request->sell_price,
                    'buy_price' => $request->buy_price,
                    'sku' => $request->sku,
                    'is_tracked' => $request->is_tracked,
                    'lower_limit' => $request->lower_limit,
            ]);
            if($itemDetailId){
                DB::commit();
                $data = ["id" => $itemDetailId];
                return $this->baseResponse->SuccessResponse(true, 201, null, $data, 1);
            }
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->baseResponse->InternalServerErrorResponse();
        }
    }
}