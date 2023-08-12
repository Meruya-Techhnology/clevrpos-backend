<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\Stock;
use App\Models\StockHistory;
use App\Models\BaseResponse;

class StockController extends Controller
{
    private $baseResponse;
    public function __construct()
    {
        $this->baseResponse = new BaseResponse();
    }

    /**
     * @OA\Post(
     *      path="/stock",
     *      operationId="createStock",
     *      tags={"Stock"},
     *      security={{"token": {}}},
     *      @OA\RequestBody(
     *          required=true,
     *          description="Stock create payload",
     *          @OA\JsonContent(
     *              required={"business_id", "outlet_id", "quantity"},
     *              @OA\Property(property="business_id", type="integer", description="Business id", example=1),
     *              @OA\Property(property="outlet_id", type="integer", description="Outlet id", example=1),
     *              @OA\Property(property="item_detail_id", type="integer", description="Purchase order id", example=1),
     *              @OA\Property(property="purchase_order_id", type="integer", description="Purchase order id", example=1),
     *              @OA\Property(property="quantity", type="integer", description="Added quantity", example=100),
     *              @OA\Property(property="expired_at", type="string", description="Created timestamp", example="2021-01-29T21:20:06.000000Z"),
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
            $checkExist = Stock::where("item_detail_id", $request->item_detail_id)->first();
            if($checkExist!= null){
                $updateStock = Stock::where("id", $checkExist->id)->update([
                    "updated_at" => $today, 
                    "updated_by" => auth()->user()->id,
                    "quantity" => $request->quantity, 
                ]);
                if($updateStock){
                    $createStockHistory = StockHistory::create([
                        "created_by" => auth()->user()->id,
                        "created_at" => $today,
                        "business_id" => $checkExist->business_id,
                        "outlet_id" => $checkExist->outlet_id,
                        "item_detail_id" => $request->item_detail_id,
                        "purchase_order_id" => $request->purchase_order_id,
                        "quantity" => $request->quantity,
                        "stock_id" => $checkExist->id,
                        "expired_at" => $request->expired_at,
                    ]);
                    if($createStockHistory){
                        DB::commit();
                        $data = ["id" => $checkExist->id];
                        return $this->baseResponse->SuccessResponse(true, 201, null, $data, 1);
                    }
                }
                return null;
            }
            $stockId = Stock::insertGetId([
                "created_by" => auth()->user()->id,
                "created_at" => $today,
                "business_id" => $request->business_id,
                "outlet_id" => $request->outlet_id,
                "item_detail_id" => $request->item_detail_id,
                "quantity" => $request->quantity,
            ]);
            if($stockId){
                $createStockHistory = StockHistory::create([
                    "created_by" => auth()->user()->id,
                    "created_at" => $today,
                    "business_id" => $request->business_id,
                    "outlet_id" => $request->outlet_id,
                    "item_detail_id" => $request->item_detail_id,
                    "purchase_order_id" => $request->purchase_order_id,
                    "quantity" => $request->quantity,
                    "stock_id" => $stockId,
                    "expired_at" => $request->expired_at,
                ]);
                if($createStockHistory){
                    DB::commit();
                    $data = ["id" => $stockId];
                    return $this->baseResponse->SuccessResponse(true, 201, null, $data, 1);
                }
            }
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->baseResponse->InternalServerErrorResponse();
        }
    }
}