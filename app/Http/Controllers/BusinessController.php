<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\Business;
use App\Models\BaseResponse;

class BusinessController extends Controller
{
    private $baseResponse;
    public function __construct()
    {
        $this->baseResponse = new BaseResponse();
    }
    /**
     * @OA\Post(
     *      path="/business",
     *      operationId="createBusiness",
     *      tags={"Business"},
     *      security={{"token": {}}},
     *      @OA\RequestBody(
     *          required=true,
     *          description="Business create payload",
     *          @OA\JsonContent(
     *              required={"name","address","business_type_id","latitude","longitude"},
     *              @OA\Property(property="name", type="string", example="Saysweet"),
     *              @OA\Property(property="address", type="string", example="Komplek permata kopo Blok F35"),
     *              @OA\Property(property="phone_number", type="string", example="6282115100216"),
     *              @OA\Property(property="email", type="string", example="admin@saysweet.com"),
     *              @OA\Property(property="business_type_id", type="integer", example=1),
     *              @OA\Property(property="sub_district_id", type="integer", example=40376),
     *              @OA\Property(property="postal_code", type="integer", example=40376),
     *              @OA\Property(property="latitude", type="number", example=-6.975753637820484),
     *              @OA\Property(property="longitude", type="number", example=107.57650660293424),
     *              @OA\Property(property="tax_name", type="string", example="Dirgham Ashauqi Zabir"),
     *              @OA\Property(property="tax_number", type="string", example="86.334.862.9-445.000"),
     *              @OA\Property(property="attachment_url", type="string", example="https://lh5.googleusercontent.com/p/AF1QipPBsvuGf1298eauB-01hzB96dplSHz0c-HquS0q=w408-h408-k-no"),
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
            $create = Business::insertGetId([
                "created_by" => auth()->user()->id,
                "created_at" => $today,
                "owner_id" => auth()->user()->id,
                "name" => $request->name,
                "address" => $request->address,
                "email" => $request->email,
                "phone_number" => $request->phone_number,
                "business_type_id" => $request->business_type_id,
                "sub_district_id" => $request->sub_district_id,
                "postal_code" => $request->postal_code,
                "latitude" => $request->latitude,
                "longitude" => $request->longitude,
                "tax_name" => $request->tax_name,
                "tax_number" => $request->tax_number,
                "attachment_url" => $request->attachment_url,
            ]);
            $data = ["id" => $create];
            if ($create) return $this->baseResponse->SuccessResponse(true, 201, null, $data, 1);
        } catch (\Exception $ex) {
            return $this->baseResponse->InternalServerErrorResponse();
        }
    }
    
    /**
     * @OA\Get(
     *     path="/business",
     *     operationId="getBusiness",
     *     tags={"Business"},
     *     security={{"token": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="",
     *         required=false,
     *         @OA\Schema(type="integer")
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
     *     @OA\Response(
     *         response="200",
     *         description="Successful response when get business",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 allOf={
     *                     @OA\Schema(ref="#/components/schemas/GetSuccessResponse"),
     *                     @OA\Schema(
     *                         @OA\Property(property="data", type="array", description="Business data",
     *                             @OA\Items(ref="#/components/schemas/GetBusiness"),
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
        $business = Business::select()
        ->offset($request->offset ?? 0)
        ->limit($request->length ?? 10)
        ->with('regional')
        ->where("owner_id", auth()->user()->id)
        ->when($request->is_deleted, function ($query, $keyword) {
            return $query->where("is_deleted", $keyword);
        })
        ->when($request->id, function ($query, $keyword) {
            return $query->where("id", $keyword);
        })
        ->when($request->searchkey, function ($query, $keyword) {
            return $query->where("name", "like", "%".$keyword."%");
        })
        ->get();
        return $this->baseResponse->SuccessResponse(true, 200, null, $business, 2);
    }

    /**
     * @OA\Put(
     *      path="/business/{id}",
     *      operationId="updateBusiness",
     *      tags={"Business"},
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
     *          description="Business update payload",
     *          @OA\JsonContent(
     *              required={"name","address","business_type_id","latitude","longitude"},
     *              @OA\Property(property="name", type="string", example="Saysweet"),
     *              @OA\Property(property="address", type="string", example="Komplek permata kopo Blok F35"),
     *              @OA\Property(property="phone_number", type="string", example="6282115100216"),
     *              @OA\Property(property="email", type="string", example="admin@saysweet.com"),
     *              @OA\Property(property="business_type_id", type="integer", example=1),
     *              @OA\Property(property="sub_district_id", type="integer", example=40376),
     *              @OA\Property(property="postal_code", type="integer", example=40376),
     *              @OA\Property(property="latitude", type="number", example=-6.975753637820484),
     *              @OA\Property(property="longitude", type="number", example=107.57650660293424),
     *              @OA\Property(property="tax_name", type="string", example="Dirgham Ashauqi Zabir"),
     *              @OA\Property(property="tax_number", type="string", example="86.334.862.9-445.000"),
     *              @OA\Property(property="attachment_url", type="string", example="https://lh5.googleusercontent.com/p/AF1QipPBsvuGf1298eauB-01hzB96dplSHz0c-HquS0q=w408-h408-k-no"),
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
            $update = Business::where("id", $request->id)
            ->update([
                "updated_at" => $today, 
                "updated_by" => auth()->user()->id,
                "name" => $request->name,
                "address" => $request->address,
                "email" => $request->email,
                "phone_number" => $request->phone_number,
                "business_type_id" => $request->business_type_id,
                "sub_district_id" => $request->sub_district_id,
                "postal_code" => $request->postal_code,
                "latitude" => $request->latitude,
                "longitude" => $request->longitude,
                "tax_name" => $request->tax_name,
                "tax_number" => $request->tax_number,
                "attachment_url" => $request->attachment_url,
            ]);
            if ($update) return $this->baseResponse->SuccessResponse(true, 200, null, null, 3);
        } catch (\Exception $ex) {
            return $this->baseResponse->InternalServerErrorResponse();
        }
    }

    /**
     * @OA\Delete(
     *      path="/business/{id}",
     *      operationId="deleteBusiness",
     *      tags={"Business"},
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
            $update = Business::where("id", $request->id)
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
}