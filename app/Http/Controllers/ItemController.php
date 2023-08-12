<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\Item;
use App\Models\ItemDetail;
use App\Models\BaseResponse;

class ItemController extends Controller
{
    private $baseResponse;
    public function __construct()
    {
        $this->baseResponse = new BaseResponse();
    }
    /**
     * @OA\Post(
     *      path="/item",
     *      operationId="createItem",
     *      tags={"Item"},
     *      security={{"token": {}}},
     *      @OA\RequestBody(
     *          required=true,
     *          description="Items create payload",
     *          @OA\JsonContent(
     *              required={"outlet_id","name"},
     *              @OA\Property(property="name", type="string", description="Item name", example="Banoffee"),
     *              @OA\Property(property="category_id", type="integer", description="Item category id", example=1),
     *              @OA\Property(property="tag", type="string", description="Item tag", example="#banoffee"),
     *              @OA\Property(property="business_id", type="integer", description="Business id", example=1),
     *              @OA\Property(property="item_detail", type="array", description="Item details",
     *                  @OA\Items(ref="#/components/schemas/CreateItemDetail"),
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
            $itemId = Item::insertGetId([
                "created_by" => auth()->user()->id,
                "created_at" => $today,
                "business_id" => $request->business_id,
                "name" => $request->name,
                "tag" => $request->tag,
                "category_id" => $request->category_id,
            ]);
            $itemDetail = [];
            foreach ($request->item_detail as $req) {
                $itemDetail[] = [
                    'created_by' => auth()->user()->id,
                    'created_at' => $today,
                    'item_id' => $itemId,
                    'variant' => $req['variant'],
                    'sell_price' => $req['sell_price'],
                    'buy_price' => $req['buy_price'],
                    'image_url' => $req['image_url'],
                    'sku' => $req['sku'],
                    'is_tracked' => $req['is_tracked'],
                    'lower_limit' => $req['lower_limit'],
                ];
            }
            $CreateItemDetail = ItemDetail::insert($itemDetail);
            if($CreateItemDetail){
                DB::commit();
                $data = ["id" => $itemId];
                return $this->baseResponse->SuccessResponse(true, 201, null, $data, 1);
            }
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->baseResponse->InternalServerErrorResponse();
        }
    }
    
    /**
     * @OA\Get(
     *     path="/item",
     *     operationId="getItem",
     *     tags={"Item"},
     *     security={{"token": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="is_tracked",
     *         in="query",
     *         description="",
     *         required=false,
     *         @OA\Schema(type="boolean", example=false)
     *     ),
     *     @OA\Parameter(
     *         name="is_deleted",
     *         in="query",
     *         description="",
     *         required=false,
     *         @OA\Schema(type="boolean", example=false)
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
     *         @OA\Schema(type="integer", example=5)
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
     *                         @OA\Property(property="data", type="array", description="Items data",
     *                             @OA\Items(ref="#/components/schemas/GetItem"),
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
        $Item = Item::with(['itemDetail.stock', 'category'])->select()
        ->offset($request->offset ?? 0)
        ->limit($request->length ?? 10)
        ->where("business_id", $request->business_id)
        ->when($request->is_deleted, function ($query, $keyword) {
            return $query->where("is_deleted", $keyword);
        })
        ->when($request->id, function ($query, $keyword) {
            return $query->where("id", $keyword);
        })
        ->when($request->searchkey, function ($query, $keyword) {
            return $query->where("name", "like", $keyword . "%");
        })
        ->get();
        return $this->baseResponse->SuccessResponse(true, 200, null, $Item, 2);
    }

    /**
     * @OA\Put(
     *      path="/item/{id}",
     *      operationId="updateItem",
     *      tags={"Item"},
     *      security={{"token": {}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Item update payload",
     *          @OA\JsonContent(
     *              @OA\Property(property="name", type="string", description="Item name", example="Banoffee"),
     *              @OA\Property(property="category_id", type="integer", description="Item category id", example=1),
     *              @OA\Property(property="tag", type="string", description="Item tag", example="#banoffee"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response="200",
     *          description="Response description",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/UpdateSuccessResponse"),
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
    
    public function Update(Request $request){
        $today = Carbon::now(new \DateTimeZone("Asia/Jakarta"));
        try {
            $update = Item::where("id", $request->id)
            ->update([
                "updated_at" => $today, 
                "updated_by" => auth()->user()->id,
                "name" => $request->name,
                "tag" => $request->tag,
                "category_id" => $request->category_id,
            ]);
            if ($update) return $this->baseResponse->SuccessResponse(true, 200, null, null, 3);
        } catch (\Exception $ex) {
            return $this->baseResponse->InternalServerErrorResponse();
        }
    }

    /**
     * @OA\Delete(
     *      path="/item/{id}",
     *      operationId="deleteItem",
     *      tags={"Item"},
     *      security={{"token": {}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response="200",
     *          description="Response description",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/DeleteSuccessResponse"),
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

    public function Delete(Request $request){
        $today = Carbon::now(new \DateTimeZone("Asia/Jakarta"));
        try {
            $update = Item::where("id", $request->id)
            ->update([
                "updated_at" => $today, 
                "updated_by" => auth()->user()->id,
                "is_deleted" => true,
            ]);
            if ($update) return $this->baseResponse->SuccessResponse(true, 200, null, null, 4);
        } catch (\Exception $ex) {
            return $this->baseResponse->InternalServerErrorResponse();
        }
    }
    /**
     * @OA\Post(
     *      path="/item",
     *      operationId="createItem",
     *      tags={"Item"},
     *      security={{"token": {}}},
     *      @OA\RequestBody(
     *          required=true,
     *          description="Items create payload",
     *          @OA\JsonContent(
     *              required={"outlet_id","name"},
     *              @OA\Property(property="name", type="string", description="Item name", example="Banoffee"),
     *              @OA\Property(property="category_id", type="integer", description="Item category id", example=1),
     *              @OA\Property(property="tag", type="string", description="Item tag", example="#banoffee"),
     *              @OA\Property(property="business_id", type="integer", description="Business id", example=1),
     *              @OA\Property(property="item_detail", type="array", description="Item details",
     *                  @OA\Items(ref="#/components/schemas/CreateItemDetail"),
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
        $today = Carbon::now(new \DateTimeZone('Asia/Jakarta'));
        try {
            DB::beginTransaction();
            $today = Carbon::now(new \DateTimeZone('Asia/Jakarta'));
            $itemId = Item::insertGetId([
                "created_by" => auth()->user()->id,
                "created_at" => $today,
                "business_id" => $request->business_id,
                "name" => $request->name,
                "tag" => $request->tag,
                "category_id" => $request->category_id,
            ]);
            $itemDetail = [];
            foreach ($request->item_detail as $req) {
                $itemDetail[] = [
                    'created_by' => auth()->user()->id,
                    'created_at' => $today,
                    'item_id' => $itemId,
                    'variant' => $req['variant'],
                    'sell_price' => $req['sell_price'],
                    'buy_price' => $req['buy_price'],
                    'sku' => $req['sku'],
                    'is_tracked' => $req['is_tracked'],
                    'lower_limit' => $req['lower_limit'],
                ];
            }
            $CreateItemDetail = ItemDetail::insert($itemDetail);
            if($CreateItemDetail){
                DB::commit();
                $data = ["id" => $itemId];
                return $this->baseResponse->SuccessResponse(true, 201, null, $data, 1);
            }
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->baseResponse->InternalServerErrorResponse();
        }
    }
    
    /**
     * @OA\Get(
     *     path="/item",
     *     operationId="getItem",
     *     tags={"Item"},
     *     security={{"token": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="is_tracked",
     *         in="query",
     *         description="",
     *         required=false,
     *         @OA\Schema(type="boolean", example=false)
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
     *         @OA\Schema(type="integer", example=5)
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
     *                         @OA\Property(property="data", type="array", description="Items data",
     *                             @OA\Items(ref="#/components/schemas/GetItem"),
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
        $Item = Item::with(['itemDetail'])->select()
        ->offset($request->offset ?? 0)
        ->limit($request->length ?? 10)
        ->where("business_id", $request->business_id)
        ->when($request->is_deleted, function ($query, $keyword) {
            return $query->where("is_deleted", $keyword);
        })
        ->when($request->id, function ($query, $keyword) {
            return $query->where("id", $keyword);
        })
        ->when($request->searchkey, function ($query, $keyword) {
            return $query->where("name", "like", $keyword . "%");
        })
        ->get();
        return $this->baseResponse->SuccessResponse(true, 200, null, $Item, 2);
    }

}