<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BaseResponse;
use App\Models\RegionalSubDistrict;
use App\Models\Regional;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class RegionalController extends Controller
{
    /// Remove the comment to use standarized response format

    private $baseResponse;
    public function __construct()
    {
        $this->baseResponse = new BaseResponse();
    }

    /// This is generated method, delete if you doesn't need it
    public function Create(Request $request){
        /// Write your code here
    }

    /**
     * @OA\Get(
     *     path="/regional",
     *     operationId="getRegional",
     *     tags={"Regional"},
     *     @OA\Parameter(
     *         name="searchkey",
     *         in="query",
     *         description="Search key",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Successful response when get regional",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 allOf={
     *                     @OA\Schema(ref="#/components/schemas/GetSuccessResponse"),
     *                     @OA\Schema(
     *                         @OA\Property(property="data", type="array", description="Regional data",
     *                             @OA\Items(ref="#/components/schemas/GetRegional"),
     *                         ),
     *                     ),
     *                 },
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Error: Bad request. When expected parameters were not supplied.",
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
     *     description="Error: Forbiden access. When user doesn't have permission to access this API.",
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
        $regional = Regional::select()
        ->when($request->searchkey, function ($query, $keyword) {
            return $query->whereRaw("SIMILARITY(name, '".$keyword."') > 0.4")
            ->orWhere("name", "ilike", "%".$keyword."%");
        })
        ->orderBy("name", "asc")
        ->limit(50)
        ->get();
        return $this->baseResponse->SuccessResponse(true, 200, null, $regional, 2);
    }

    public function Update(Request $request){
        
        /// Write your code here
    }
    public function Delete(Request $request){
        /// Write your code here
    }
}