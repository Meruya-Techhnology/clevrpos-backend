<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\Outlet;
use App\Models\BaseResponse;

class OutletController extends Controller
{
    private $baseResponse;
    public function __construct()
    {
        $this->baseResponse = new BaseResponse();
    }
    /**
     *  @OA\Post(
     *      path="/outlet",
     *      operationId="createOutlet",
     *      tags={"Outlet"},
     *      security={{"token": {}}},
     *      @OA\RequestBody(
     *          required=true,
     *          description="Outlet create payload",
     *          @OA\JsonContent(
     *              required={"name", "code", "address", "latitude", "longitude", "business_id", "sub_district_id", "postal_code"},
     *              @OA\Property(property="name", type="string", example="Saysweet"),
     *              @OA\Property(property="code", type="integer", example="SSK"),
     *              @OA\Property(property="address", type="string", example="Komplek permata kopo Blok F35"),
     *              @OA\Property(property="latitude", type="number", description="Latitude coordinate", example=-6.9754222),
     *              @OA\Property(property="longitude", type="number", description="Longitude coordinate", example=107.577356),
     *              @OA\Property(property="business_id", type="integer", description="Business id", example=1),
     *              @OA\Property(property="sub_district_id", type="integer", description="Sub district id", example= "40212"),
     *              @OA\Property(property="phone_number", type="string", description="User phone number", example="082115100216"),
     *              @OA\Property(property="postal_code", type="integer", description="Outlet postal code", example=40376),
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
     *          description="Error: Forbiden access. When rest-api client isn't authorized to use this API",
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
     *          description="Error: Forbiden access. When user doesn't have permission to access this API.",
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
        try{
            $today = Carbon::now(new \DateTimeZone("Asia/Jakarta"));
            $create = Outlet::create([
                "created_by" => auth()->user()->id, 
                "created_at" => $today, 
                "name" => $request->name, 
                "code" => $request->code, 
                "address" => $request->address, 
                "postal_code" => $request->postal_code, 
                "latitude" => $request->latitude, 
                "longitude" => $request->longitude, 
                "business_id" => $request->business_id, 
                "sub_district_id" => $request->sub_district_id, 
                "phone_number" => $request->phone_number, 
            ]);
            if ($create) return $this->baseResponse->SuccessResponse(true, 201, null, null, 1);
        }
        catch(\Exception $ex){
            return $this->baseResponse->InternalServerErrorResponse();
        }
    }
    
    /**
     * @OA\Get(
     *     path="/outlet",
     *     operationId="getOutlet",
     *     tags={"Outlet"},
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
     *                         @OA\Property(property="data", type="array", description="Outlet data",
     *                             @OA\Items(ref="#/components/schemas/GetOutlet"),
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
    public function Select(Request $request){
        $outlet = Outlet::select()
        ->with(['regional'])
        ->where("business_id", $request->business_id)
        ->when($request->id, function ($query, $keyword){
            return $query->where("id", $keyword);
        })->when($request->searchkey, function ($query, $keyword){
            return $query->where("name", "like", $keyword . "%");
        })
        ->get()
        ->toArray();
        return $this->baseResponse->SuccessResponse(true, 200, null, $outlet, 2);
    }

    /**
     * @OA\Put(
     *      path="/outlet/{id}",
     *      operationId="updateOutlet",
     *      tags={"Outlet"},
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
     *          description="Outlet update payload",
     *          @OA\JsonContent(
     *              required={"name", "code", "address", "latitude", "longitude", "business_id", "sub_district_id"},
     *              @OA\Property(property="name", type="string", example="Saysweet"),
     *              @OA\Property(property="code", type="integer", example="SSK"),
     *              @OA\Property(property="address", type="string", example="Komplek permata kopo Blok F35"),
     *              @OA\Property(property="latitude", type="number", description="Latitude coordinate", example=-6.9754222),
     *              @OA\Property(property="longitude", type="number", description="Longitude coordinate", example=107.577356),
     *              @OA\Property(property="sub_district_id", type="integer", description="Sub district id", example= "40212"),
     *              @OA\Property(property="phone_number", type="string", description="User phone number", example="082115100216"),
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
            $update = Outlet::where("id", $request->id)
            ->update([
                "updated_at" => $today, 
                "updated_by" => auth()->user()->id,
                "name" => $request->name, 
                "code" => $request->code, 
                "address" => $request->address, 
                "latitude" => $request->latitude, 
                "longitude" => $request->longitude, 
                "sub_district_id" => $request->sub_district_id, 
                "phone_number" => $request->phone_number, 
            ]);
            if ($update) return $this->baseResponse->SuccessResponse(true, 200, null, null, 3);
        } catch (\Exception $ex) {
            return $this->baseResponse->InternalServerErrorResponse();
        }
    }

    /**
     * @OA\Delete(
     *      path="/outlet/{id}",
     *      operationId="deleteOutlet",
     *      tags={"Outlet"},
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
            $update = Outlet::where("id", $request->id)
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

