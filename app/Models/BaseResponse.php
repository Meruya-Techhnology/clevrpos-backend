<?php
namespace App\Models;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;

class BaseResponse
{
    // protected $status;
    // protected $code;
    // protected $message;
    // protected $data;

    // public function __construct(bool $status, int $code, String $message, array $data)
    // {
    //     $this->status = $status;
    //     $this->code = $code;
    //     $this->message = $message;
    //     $this->data = $data;
    // }

    public function CustomArray($status, $code, $message, $data){
        $response = [
            "status" => $status,
            "code" => $code,
            "message" => $message,
        ];
        if($data!=null) $response["data"] = $data;
        return $response;
    }

    public function CustomResponse($status, $code, $message, $data){
        $response = [
            "status" => $status,
            "code" => $code,
            "message" => $message,
        ];
        if($data!=null) $response["data"] = $data;
        return response()->json($response,$code);
    }

    public function SuccessResponse($status, $code, $message, $data, $flag){
        if($message == null){
            switch ($flag) {
                /// 1 for create
                case '1':
                    $code = 201;
                    $message = "Data created successfully";
                    break;
                /// 2 for get
                case '2':
                    $code = 200;
                    $message = "Data fetched successfully";
                    break;
                /// 3 for update
                case '3':
                    $code = 200;
                    $message = "Data updated successfully";
                    break;
                /// 4 for delete
                case '4':
                    $code = 200;
                    $message = "Data deleted successfully";
                    break;
                /// 5 for custom action
                case '5':
                    $code = 200;
                    $message = "Action executed successfully";
                    break;
                default:
                    $code = 200;
                    $message = "Response success";
                    break;
            }
        }
        $response = [
            "status" => $status,
            "code" => $code,
            "message" => $message,
        ];
        if($data!=null) $response["data"] = $data;
        return response()->json($response,$code);
    }
    /// Basic properties
    /// Default 200 response
    
    /**
     *  @OA\Schema(
     *      schema="GetSuccessResponse",
     *      allOf={
     *          @OA\Schema(
     *               @OA\Property(property="status", type="string", description="Expected status", example=true),
     *               @OA\Property(property="code", type="integer", description="HTTP Code", example=200),
     *               @OA\Property(property="message", type="string", description="Message", example="Data fetched successfully"),
     *          ),
     *      },
     * ),
     */

    /// Created with data
    /// Default 201 response

    /**
     *  @OA\Schema(
     *      schema="CreateSuccessResponse",
     *      allOf={
     *          @OA\Schema(
     *               @OA\Property(property="status", type="string", description="Expected status", example=true),
     *               @OA\Property(property="code", type="integer", description="HTTP Code", example=201),
     *               @OA\Property(property="message", type="string", description="Message", example="Data created successfully"),
     *          ),
     *      },
     * ),
     */
     
    /**
     *  @OA\Schema(
     *      schema="UpdateSuccessResponse",
     *      allOf={
     *          @OA\Schema(
     *               @OA\Property(property="status", type="string", description="Expected status", example=true),
     *               @OA\Property(property="code", type="integer", description="HTTP Code", example=200),
     *               @OA\Property(property="message", type="string", description="Message", example="Data updated successfully"),
     *          ),
     *      },
     * ),
     */
     
    /**
     *  @OA\Schema(
     *      schema="DeleteSuccessResponse",
     *      allOf={
     *          @OA\Schema(
     *               @OA\Property(property="status", type="string", description="Expected status", example=true),
     *               @OA\Property(property="code", type="integer", description="HTTP Code", example=200),
     *               @OA\Property(property="message", type="string", description="Message", example="Data deleted successfully"),
     *          ),
     *      },
     * ),
     */

    /**
     *  @OA\Schema(
     *      schema="UpdateNothingResponse",
     *      allOf={
     *          @OA\Schema(
     *               @OA\Property(property="status", type="string", description="Expected status", example=false),
     *               @OA\Property(property="code", type="integer", description="HTTP Code", example=202),
     *               @OA\Property(property="message", type="string", description="Message", example="Update not initiated"),
     *          ),
     *      },
     * ),
     */

    /// Bad Request
    /// Default 400 response
    
    /**
     *  @OA\Schema(
     *      schema="BadRequest",
     *      allOf={
     *          @OA\Schema(
     *               @OA\Property(property="status", type="string", description="Result status", example=false),
     *               @OA\Property(property="code", type="integer", description="Error code", example=400),
     *               @OA\Property(property="message", type="string", description="Message", example="Bad request"),
     *          ),
     *      },
     * ),
     */
     
    public function BadRequestResponse($data){
        $response = [
            "status" => false,
            "code" => 400,
            "message" => "Bad request",
        ];
        if($data!=null) $response["error_message"] = $data;
        return response()->json($response,400);
    }


    /// Unauthrorized
    /// Default 401 response
    
    /**
     *  @OA\Schema(
     *      schema="Unauthorized",
     *      allOf={
     *          @OA\Schema(
     *               @OA\Property(property="status", type="string", description="Result status", example=false),
     *               @OA\Property(property="code", type="integer", description="Error code", example=401),
     *               @OA\Property(property="message", type="string", description="Message", example="Unauthorized action"),
     *          ),
     *      },
     * ),
     */
    
    public function UnauthorizedResponse(){
        $response = [
            "status" => false,
            "code" => 401,
            "message" => "Unauthorized access",
        ];
        return response()->json($response,401);
    }

    /// Forbiden Access
    /// Default 403 response
        
    /**
     *  @OA\Schema(
     *      schema="ForbidenAccess",
     *      allOf={
     *          @OA\Schema(
     *               @OA\Property(property="status", type="string", description="Result status", example=false),
     *               @OA\Property(property="code", type="integer", description="Error code", example=403),
     *               @OA\Property(property="message", type="string", description="Message", example="Forbiden access"),
     *          ),
     *      },
     * ),
     */
    
    public function ForbidenAccessResponse(){
        $response = [
            "status" => false,
            "code" => 403,
            "message" => "Forbiden Access",
        ];
        return response()->json($response,403);
    }

    /// NotFound
    /// Default 404 response
        
    /**
     *  @OA\Schema(
     *      schema="NotFound",
     *      allOf={
     *          @OA\Schema(
     *               @OA\Property(property="status", type="string", description="Result status", example=false),
     *               @OA\Property(property="code", type="integer", description="Error code", example=404),
     *               @OA\Property(property="message", type="string", description="Message", example="Data not found"),
     *          ),
     *      },
     * ),
     */

    
    public function NotFoundResponse($message){
        $response = [
            "status" => false,
            "code" => 404,
            "message" => $message ?? "Data not found",
        ];
        return response()->json($response,404);
    }

    /// Internal server error
    /// Default 500 response
    
    /**
     *  @OA\Schema(
     *      schema="InternalServerError",
     *      allOf={
     *          @OA\Schema(
     *               @OA\Property(property="status", type="string", description="Result status", example=false),
     *               @OA\Property(property="code", type="integer", description="Error code", example=500),
     *               @OA\Property(property="message", type="string", description="Message", example="Internal server error"),
     *          ),
     *      },
     * ),
     */
    
    public function InternalServerErrorResponse(){
        $response = [
            "status" => false,
            "code" => 500,
            "message" => "Internal server error",
        ];
        return response()->json($response,500);
    }
}

