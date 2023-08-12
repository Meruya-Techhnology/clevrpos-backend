<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\Supplier;
use App\Models\BaseResponse;

class SupplierController extends Controller
{
    private $baseResponse;
    public function __construct()
    {
        $this->baseResponse = new BaseResponse();
    }

    /**
     * @OA\Post(
     *      path="/supplier",
     *      operationId="createSupplier",
     *      tags={"Supplier"},
     *      security={{"token": {}}},
     *      @OA\RequestBody(
     *          required=true,
     *          description="Supplier create payload",
     *          @OA\JsonContent(
     *              required={"name","address","business_id"},
     *              @OA\Property(property="name", type="string", example="Saysweet"),
     *              @OA\Property(property="address", type="string", example="Komplek permata kopo Blok F35"),
     *              @OA\Property(property="phone_number", type="string", example="6282115100216"),
     *              @OA\Property(property="email", type="string", example="admin@saysweet.com"),
     *              @OA\Property(property="business_id", type="integer", example=1),
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
            $today = Carbon::now(new \DateTimeZone('Asia/Jakarta'));
            $create = Supplier::insertGetId([
                "created_by" => auth()->user()->id,
                "created_at" => $today,
                "name" => $request->name,
                "address" => $request->address,
                "email" => $request->email,
                "phone_number" => $request->phone_number,
                "business_id" => $request->business_id,
            ]);
            $data = ["id" => $create];
            if ($create) return $this->baseResponse->SuccessResponse(true, 201, null, $data, 1);
        } catch (\Exception $ex) {
            print($ex);
            return $this->baseResponse->InternalServerErrorResponse();
        }
    }
    
    /**
     * @OA\Get(
     *     path="/supplier",
     *     operationId="getSupplier",
     *     tags={"Supplier"},
     *     security={{"token": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="business_id",
     *         in="query",
     *         description="This key is required in order to get outlet which owned by business id",
     *         required=true,
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
     *     @OA\Response(
     *         response="200",
     *         description="Successful response when get outlet",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 allOf={
     *                     @OA\Schema(ref="#/components/schemas/GetSuccessResponse"),
     *                     @OA\Schema(
     *                         @OA\Property(property="data", type="array", description="Supplier data",
     *                             @OA\Items(ref="#/components/schemas/GetSupplier"),
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
     *          response="403",
     *          description="Error: Forbiden access. When user doesn't have permission to access this API.",
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
    public function Select(Request $request)
    {
        $supplier = Supplier::select()
        ->where("business_id", $request->business_id)
        ->when($request->id, function ($query, $keyword){
            return $query->where("id", $keyword);
        })->when($request->searchkey, function ($query, $keyword){
            return $query->where("name", "like", $keyword . "%");
        })
        ->get()
        ->toArray();
        return $this->baseResponse->SuccessResponse(true, 200, null, $supplier, 2);
    }
}