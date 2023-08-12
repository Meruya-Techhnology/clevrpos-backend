<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BaseResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\BusinessType;


class BusinessTypeController extends Controller
{

    private $baseResponse;
    public function __construct()
    {
        $this->baseResponse = new BaseResponse();
    }

    /**
     * @OA\Post(
     *      path="/business_type",
     *      operationId="createBusinessType",
     *      tags={"Business Type"},
     *      security={{"token": {}}},
     *      @OA\RequestBody(
     *          required=true,
     *          description="Business create payload",
     *          @OA\JsonContent(
     *              required={"name"},
     *              @OA\Property(property="name", type="string", example="Bakery & Pastry"),
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
            $create = BusinessType::create([
                "created_by" => auth()->user()->id,
                "created_at" => $today,
                "owner_id" => auth()->user()->id,
                "name" => $request->name,
            ]);
            if ($create) return $this->baseResponse->SuccessResponse(true, 201, null, null, 1);
        } catch (\Exception $ex) {
            return $this->baseResponse->InternalServerErrorResponse();
        }
    }
    
    /**
     * @OA\Get(
     *     path="/business_type",
     *     operationId="getBusinessType",
     *     tags={"Business Type"},
     *     @OA\Parameter(
     *         name="searchkey",
     *         in="query",
     *         description="",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Successful response when get business type",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 allOf={
     *                     @OA\Schema(ref="#/components/schemas/GetSuccessResponse"),
     *                     @OA\Schema(
     *                         @OA\Property(property="data", type="array", description="Business type data",
     *                             @OA\Items(ref="#/components/schemas/GetBusinessType"),
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
        $businessType = BusinessType::select()
        ->when($request->searchkey, function ($query, $keyword) {
            return $query->where("name", "like", $keyword . "%");
        })
        ->get();
        return $this->baseResponse->SuccessResponse(true, 200, null, $businessType, 2);
    }
    
    public function Update(Request $request){
        /// Write your code here
    }
    public function Delete(Request $request){
        /// Write your code here
    }
}