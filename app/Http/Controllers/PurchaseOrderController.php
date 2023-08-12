<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\BaseResponse;

class PurchaseOrderController extends Controller
{
    private $baseResponse;
    public function __construct()
    {
        $this->baseResponse = new BaseResponse();
    }

    /**
     * @OA\Post(
     *      path="/purchase_order",
     *      operationId="createPurchaseOrder",
     *      tags={"Purchase Order"},
     *      security={{"token": {}}},
     *      @OA\RequestBody(
     *          required=true,
     *          description="Items create payload",
     *          @OA\JsonContent(
     *              required={"business_id","outlet_id"},
     *              @OA\Property(property="business_id", type="integer", description="Business id", example=1),
     *              @OA\Property(property="outlet_id", type="integer", description="Outlet id", example=1),
     *              @OA\Property(property="supplier_id", type="integer", description="Supplier id", example=1),
     *              @OA\Property(property="purchase_order_detail", type="array", description="Puchase order detail",
     *                  @OA\Items(ref="#/components/schemas/CreatePurchaseOrderDetail"),
     *              ),
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
            $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $orderDate = Carbon::now()->format('dmY');
            $orderNumber = "PO-".$orderDate.substr(str_shuffle($permitted_chars), 0, 4);
            $purchaseOrderId = PurchaseOrder::insertGetId([
                "created_by" => auth()->user()->id,
                "created_at" => $today,
                "order_number" => $orderNumber,
                "business_id" => $request->business_id,
                "outlet_id" => $request->outlet_id,
                "supplier_id" => $request->supplier_id,
            ]);
            $purchaseOrderDetail = [];
            foreach ($request->purchase_order_detail as $req) {
                $purchaseOrderDetail[] = [
                    'created_by' => auth()->user()->id,
                    'created_at' => $today,
                    'purchase_order_id' => $purchaseOrderId,
                    'item_detail_id' => $req['item_detail_id'],
                    'quantity' => $req['quantity'],
                ];
            }
            $createPurchaseOrderDetail = PurchaseOrderDetail::insert($purchaseOrderDetail);
            if($createPurchaseOrderDetail){
                DB::commit();
                $data = ["id" => $purchaseOrderId];
                return $this->baseResponse->SuccessResponse(true, 201, null, $data, 1);
            }
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->baseResponse->InternalServerErrorResponse();
        }
    }
    
    /**
     * @OA\Get(
     *     path="/purchase_order",
     *     operationId="getPurchaseOrder",
     *     tags={"Purchase Order"},
     *     security={{"token": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="searchkey",
     *         in="query",
     *         description="",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="offset",
     *         in="query",
     *         description="Default 0",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Default 10",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="business_id",
     *         in="query",
     *         description="Business id",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="outlet_id",
     *         in="query",
     *         description="Outlet id",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="supplier_id",
     *         in="query",
     *         description="Supplier id",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Successful response when get Item",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 allOf={
     *                     @OA\Schema(ref="#/components/schemas/GetSuccessResponse"),
     *                     @OA\Schema(
     *                         @OA\Property(property="data", type="array", description="Purchase order data",
     *                             @OA\Items(ref="#/components/schemas/GetPurchaseOrder"),
     *                         ),
     *                     ),
     *                 },
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Error: Forbiden access. When rest-api client isn't authorized to use this API",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/BadRequest"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Error: Unauthorized. When rest-api client isn't authorized to use this API",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/Unauthorized"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="403",
     *         description="Error: Forbiden access. When user doesn't have permission to access this API.",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/ForbidenAccess"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Error: Internal server error. This is happen when server had internal error",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/InternalServerError"),
     *         ),
     *     ),
     * )
     */
    public function Select(Request $request){
        $id = $request->input("id", null);
        $Item = PurchaseOrder::with(['purchaseOrderDetail.itemDetail.item', 'supplier', 'outlet'])->select()
        ->offset($request->offset ?? 0)
        ->limit($request->length ?? 10)
        ->where("business_id", $request->business_id)
        ->when($request->id, function ($query, $keyword) {
            return $query->where("id", $keyword);
        })
        ->when($request->outlet_id, function ($query, $keyword) {
            return $query->where("outlet_id", $keyword);
        })
        ->when($request->supplier_id, function ($query, $keyword) {
            return $query->where("supplier_id", $keyword);
        })
        ->when($request->searchkey, function ($query, $keyword) {
            return $query->where("order_number", "like", $keyword . "%");
        })
        ->get();
        return $this->baseResponse->SuccessResponse(true, 200, null, $Item, 2);
    }
    public function Update(Request $request){
        /// Write your code here
    }
    public function Delete(Request $request){
        /// Write your code here
    }
}