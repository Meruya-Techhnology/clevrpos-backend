<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\UserConfirmation;
use App\Models\OauthAccessToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\SendVerification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use App\Models\BaseResponse;
use Twilio\Rest\Client as TwilioClient;

class UserController extends Controller
{
    private $baseResponse;
    public function __construct()
    {
        $this->baseResponse = new BaseResponse();
    }

    /**
     * @OA\Post(
     *      path="/auth/login",
     *      operationId="login",
     *      tags={"Auth"},
     *      @OA\RequestBody(
     *          required=true,
     *          description="Login payload",
     *          @OA\JsonContent(
     *              required={"email","password"},
     *              @OA\Property(property="email", type="string", example="dwikurnianto.mulyadien@gmail.com"),
     *              @OA\Property(property="password", type="string", example="Echodelta541"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response="200",
     *          description="Response description",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(property="status", type="string", description="Expected status", example=true),
     *                  @OA\Property(property="code", type="integer", description="Http code in the body", example=200),
     *                  @OA\Property(property="message", type="string", description="Message", example="Login successful"),
     *                  @OA\Property(property="data", type="object", description="Oauth data",ref="#/components/schemas/OauthToken"),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response="400",
     *          description="Error: Forbiden access. When rest-api client not authorized to use this API",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  allOf={
     *                      @OA\Schema(ref="#/components/schemas/BadRequest"),
     *                      @OA\Schema(
     *                          @OA\Property(property="error_message", type="object", description="Error Message",
     *                              @OA\Property(property="email", type="array",
     *                                  @OA\Items(type="string", example="E-mail is required"),
     *                              ),
     *                              @OA\Property(property="password", type="array",
     *                                  @OA\Items(type="string", example="Password is required"),
     *                              ),
     *                          ),
     *                      ),
     *                  },
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response="401",
     *          description="Error: Unauthorized. When rest-api client not authorized to use this API",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/Unauthorized"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response="403",
     *          description="Error: Forbiden access. When user doesn't have permission to access this API.",
     *          @OA\MediaType(
     *               mediaType="application/json",
     *               @OA\Schema(
     *                   @OA\Property(property="status", type="string", description="Expected status", example=false),
     *                   @OA\Property(property="code", type="integer", description="Http code in the body", example=403),
     *                   @OA\Property(property="message", type="string", description="Message", example="Invalid credential / User is not activated / Forbiden access"),
     *                   @OA\Property(property="data", type="object", description="data",
     *                       @OA\Property(property="action_type", type="integer", description="1 for redirect otp, 2 for invalid password", example=1),
     *                       @OA\Property(property="phone_number", type="string", description="User phone number", example="6282115100216"),
     *                   )
     *               )
     *          ),
     *      ),
     *      @OA\Response(
     *          response="404",
     *          description="Error: Account not found. When required parameters were not supplied.",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(property="status", type="string", description="Expected status", example=false),
     *                  @OA\Property(property="code", type="integer", description="Http code in the body", example=404),
     *                  @OA\Property(property="message", type="string", description="Message", example="Unauthorized action"),
     *              )
     *          )
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

    public function Login(Request $request){
        try{
            $validator = Validator::make($request->all() , [
                "email" => "email|required", 
                "password" => "required", ], [
                    "email.required" => "E-mail cannot empty", 
                    "email.email" => "Please use correct e-mail format example@email.com", 
                    "password.required" => "Password cannot empty", 
                ]);
            if ($validator->fails()) return $this->baseResponse->BadRequestResponse($validator->errors());
            $user = User::where("email", $request->email)->first();
            if (!$user) return $this->baseResponse->UnauthorizedResponse();
            if (!$user->is_active) return $this->baseResponse->CustomResponse(false, 403, "User is not activated", ["action_type" => 1, "phone_number" => $user->phone_number]);
            if (Hash::check($request->password, $user->password)){
                $postRequest = ["grant_type" => "password", 'client_id' => "2", 'client_secret' => "KTiytmYX8Lr829AD0L3CH2L6hq4S2yPwA4dlIj6l", 'username' => $request->email, 'password' => $request->password, 'scope' => '*'];
                $tokenRequest = Request::create(env('APP_URL') . '/oauth/token', 'post', $postRequest);
                $response = app()->handle($tokenRequest);
                if ($response->getStatusCode() != 200) return $this->baseResponse->ForbidenAccess();
                $response = json_decode($response->getContent());
                return $this->baseResponse->SuccessResponse(true, 200, "Login success", $response, 5);
            }
            return $this->baseResponse->CustomResponse(false, 403, "Invalid credential", ["action_type" => 2]);
        }
        catch(\Exception $ex){
            return $this->baseResponse->InternalServerErrorResponse();
        }
    }

    /**
     * @OA\Post(
     *      path="/auth/register",
     *      operationId="register",
     *      tags={"Auth"},
     *      @OA\RequestBody(
     *          required=true,
     *          description="Register payload",
     *          @OA\JsonContent(
     *              required={"email","password","phone_number","name"},
     *              @OA\Property(property="email", type="string", example="dwikurnianto.mulyadien@gmail.com"),
     *              @OA\Property(property="password", type="int", example="123456"),
     *              @OA\Property(property="phone_number", type="string", example="082115100216"),
     *              @OA\Property(property="name", type="string", example="Dwi Kurnianto Mulyadien"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response="200",
     *          description="Response description",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(property="status", type="string", description="Expected status", example=true),
     *                  @OA\Property(property="code", type="integer", description="Http code in the body", example=200),
     *                  @OA\Property(property="message", type="string", description="Message", example="Registrasi berhasil"),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response="400",
     *          description="Error: Bad request. When expected parameters were not supplied.",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  allOf={
     *                      @OA\Schema(ref="#/components/schemas/BadRequest"),
     *                      @OA\Schema(
     *                          @OA\Property(property="error_message", type="object", description="Error Message",
     *                              @OA\Property(property="name", type="array",
     *                                  @OA\Items(type="string", example="User name cannot empty"),
     *                              ),
     *                              @OA\Property(property="email", type="array",
     *                                  @OA\Items(type="string", example="E-mail already used"),
     *                              ),
     *                              @OA\Property(property="phone_number", type="array",
     *                                  @OA\Items(type="string", example="Phone number already used"),
     *                              ),
     *                              @OA\Property(property="password", type="array",
     *                                  @OA\Items(type="string", example="Password key cannot empty"),
     *                              ),
     *                          ),
     *                      ),
     *                  },
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response="401",
     *          description="Error: Unauthorized. When rest-api client not authorized to use this API",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/Unauthorized"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response="403",
     *          description="Error: Forbiden access. When user doesn't have permission to access this API.",
     *          @OA\MediaType(
     *               mediaType="application/json",
     *               @OA\Schema(ref="#/components/schemas/ForbidenAccess"),
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
    public function Register(Request $request){
        DB::beginTransaction();
        try{
            $plainKey = Str::random(60);
            $storageId = Str::random(60);
            $storedKey = Crypt::encryptString($plainKey);
            $today = Carbon::now(new \DateTimeZone("Asia/Jakarta"));
            $validator = Validator::make($request->all() , [
                "name" => "required", 
                "email" => "required|email|unique:users", 
                "phone_number" => "required|unique:users", 
                "password" => "required", ], [
                    "name.required" => "User name cannot empty", 
                    "email.required" => "E-mail cannot empty", 
                    "email.unique" => "E-mail already used", 
                    "email.email" => "Please use correct e-mail format example@email.com", 
                    "phone_number.required" => "Phone number cannot empty", 
                    "phone_number.unique" => "Phone number already used", 
                    "password.required" => "Password cannot empty", 
                ]);
            if ($validator->fails()) return $this->baseResponse->BadRequestResponse($validator->errors());
                $user = User::create([
                    "created_at" => $today, 
                    "name" => $request->name, 
                    "phone_number" => $request->phone_number, 
                    "email" => $request->email, 
                    "password" => Hash::make($request->password),
                    "storage_id" => $storageId,
                ]);
            if ($user){
                $createConfirmation = UserConfirmation::create([
                    "created_at" => $today, 
                    "created_by" => $user->id, 
                    "user_id" => $user->id, 
                    "access_key" => $storedKey, 
                    "confirmation_type" => 2,
                    "expired_at" => Carbon::now(new \DateTimeZone("Asia/Jakarta"))->addDays(7),
                ]);
                if ($createConfirmation){
                    $mailData = ["name" => $request->name, "email" => $request->email, "access_code" => $plainKey];
                    $sendMail = Mail::to($request["email"])->send(new SendVerification($mailData));
                    DB::commit();
                    return $this->baseResponse->SuccessResponse(true, 201, "Account registered successfully", null, 5);
                }
            }
        }
        catch(\Exception $ex){
            DB::rollback();
            return $this->baseResponse->InternalServerErrorResponse();
        }
    }

    /**
     * @OA\Get(
     *     path="/auth/profile",
     *     operationId="getProfile",
     *     tags={"Auth"},
     *     security={{"token": {}}},
     *     @OA\Response(
     *         response="200",
     *         description="Response description",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(property="status", type="string", description="Expected status", example=true),
     *                  @OA\Property(property="code", type="integer", description="Http code in the body", example=200),
     *                  @OA\Property(property="message", type="string", description="Message", example="Login successful"),
     *                  @OA\Property(property="data", type="object", description="Oauth data",ref="#/components/schemas/User"),
     *              )
     *          )
     *     ),
     *      @OA\Response(
     *          response="400",
     *          description="Error: Forbiden access. When rest-api client not authorized to use this API",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/BadRequest"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response="401",
     *          description="Error: Unauthorized. When rest-api client not authorized to use this API",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/Unauthorized"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response="403",
     *          description="Error: Forbiden access. When user doesn't have permission to access this API.",
     *          @OA\MediaType(
     *               mediaType="application/json",
     *               @OA\Schema(ref="#/components/schemas/ForbidenAccess"),
     *          ),
     *      ),
     *     @OA\Response(
     *          response="500",
     *          description="Error: Internal server error. This is happen when server had internal error",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/InternalServerError"),
     *          ),
     *     ),
     * )
     */

    public function Profile(Request $request){
        try{
            return $this->baseResponse->SuccessResponse(true, 200, "Success Get Profile", auth()->user()->setHidden(["id"]) , 5);
        }
        catch(\Exception $ex){
            return $this->baseResponse->InternalServerErrorResponse();
        }
    }

    /**
     * @OA\Put(
     *     path="/auth/profile",
     *     operationId="updateProfile",
     *     tags={"Auth"},
     *     security={{"token": {}}},
     *      @OA\RequestBody(
     *          required=true,
     *          description="Register payload",
     *          @OA\JsonContent(
     *              required={"name"},
     *              @OA\Property(property="name", type="string", example="Dwi Kurnianto Mulyadien"),
     *          ),
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="Response description",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(property="status", type="string", description="Expected status", example=true),
     *                  @OA\Property(property="code", type="integer", description="Http code in the body", example=200),
     *                  @OA\Property(property="message", type="string", description="Message", example="Profile successfully update"),
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *         response="202",
     *         description="Response description",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/UpdateNothingResponse"),
     *          ),
     *     ),
     *      @OA\Response(
     *          response="400",
     *          description="Error: Forbiden access. When rest-api client not authorized to use this API",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/BadRequest"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response="401",
     *          description="Error: Unauthorized. When rest-api client not authorized to use this API",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/Unauthorized"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response="403",
     *          description="Error: Forbiden access. When user doesn't have permission to access this API.",
     *          @OA\MediaType(
     *               mediaType="application/json",
     *               @OA\Schema(ref="#/components/schemas/ForbidenAccess"),
     *          ),
     *      ),
     *      @OA\Response(
     *           response="500",
     *           description="Error: Internal server error. This is happen when server had internal error",
     *           @OA\MediaType(
     *               mediaType="application/json",
     *               @OA\Schema(ref="#/components/schemas/InternalServerError"),
     *           ),
     *      ),
     * )
     */
    public function UpdateProfile(Request $request){
        try{
            $today = Carbon::now(new \DateTimeZone("Asia/Jakarta"));
            $update = User::where("id", auth()->user()->id)
                ->update([
                    "updated_at" => $today, 
                    "updated_by" => auth()->user()->id, 
                    "name" => $request["name"]
                ]);
            if ($update) return $this->baseResponse->SuccessResponse(true, 200, "Profile successfully updated", null, 5);
            else return $this->baseResponse->SuccessResponse(true, 202, "Update not initiated", null, 5);
        }
        catch(\Exception $ex)
        {
            return $this->baseResponse->InternalServerErrorResponse();
        }
    }

    /**
     * @OA\Post(
     *      path="/auth/send-otp",
     *      operationId="sendOtp",
     *      tags={"Auth"},
     *      @OA\RequestBody(
     *          required=true,
     *          description="Send-Otp payload",
     *          @OA\JsonContent(
     *              required={"phone_number", "sign_key"},
     *              @OA\Property(property="phone_number", type="string", example="6282115100216"),
     *              @OA\Property(property="sign_key", type="string", example="cxf23ja")
     *          ),
     *      ),
     *      @OA\Response(
     *          response="200",
     *          description="Response description",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(property="status", type="string", description="Expected status", example=true),
     *                  @OA\Property(property="code", type="integer", description="Http code in the body", example=200),
     *                  @OA\Property(property="message", type="string", description="Message", example="Otp sended successfully"),
     *                  @OA\Property(property="data", type="object", description="data",
     *                      @OA\Property(property="sign_key", type="string", description="Signature key", example="cxf23ja"),
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response="400",
     *          description="Error: Bad request. When expected parameters were not supplied.",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  allOf={
     *                      @OA\Schema(ref="#/components/schemas/BadRequest"),
     *                      @OA\Schema(
     *                          @OA\Property(property="error_message", type="object", description="Error Message",
     *                              @OA\Property(property="phone_number", type="array",
     *                                  @OA\Items(type="string", example="Phone number cannot empty"),
     *                              ),
     *                              @OA\Property(property="sign_key", type="array",
     *                                  @OA\Items(type="string", example="Phone key cannot empty"),
     *                              ),
     *                          ),
     *                      ),
     *                  },
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response="401",
     *          description="Error: Unauthorized. When rest-api client not authorized to use this API",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/Unauthorized"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response="403",
     *          description="Error: Forbiden access. When user doesn't have permission to access this API.",
     *          @OA\MediaType(
     *               mediaType="application/json",
     *               @OA\Schema(
     *                   allOf={
     *                       @OA\Schema(ref="#/components/schemas/ForbidenAccess"),
     *                       @OA\Schema(
     *                           @OA\Property(property="data", type="object", description="data",
     *                               @OA\Property(property="action_type", type="integer", description="1 for redirect otp, 2 for invalid password", example=1),
     *                           ),
     *                       ),
     *                   },
     *               ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response="404",
     *          description="Error: Account not found. Server cannot find the right user",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(property="status", type="string", description="Expected status", example=false),
     *                  @OA\Property(property="code", type="integer", description="Http code in the body", example=404),
     *                  @OA\Property(property="message", type="string", description="Message", example="Account not found"),
     *              )
     *          )
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
    public function SendOtp(Request $request){
        try{
            $validator = Validator::make($request->all() , [
                "phone_number" => "required", 
                "sign_key" => "required", ], [
                    "phone_number.required" => "Phone number cannot empty", 
                    "sign_key.required" => "Sign key cannot empty", 
                ]);
            if ($validator->fails()) return $this->baseResponse->BadRequestResponse($validator->errors());
            DB::beginTransaction();
            $today = Carbon::now(new \DateTimeZone("Asia/Jakarta"));
            $permittedChars = "0123456789";
            $token = getenv("TWILIO_AUTH_TOKEN");
            $twilioSid = getenv("TWILIO_SID");
            $twilioNumber = getenv("TWILIO_NUMBER");
            $twilio = new TwilioClient($twilioSid, $token);
            $otpCode = substr(str_shuffle($permittedChars), 0, 5);
            $user = User::where("phone_number", $request->phone_number)->first();
            if (!$user) return $this->baseResponse->NotFoundResponse("Account not found");
            /// confirmation type
            /// 1 for account confirmation (sms otp)
            /// 2 for e-mail confirmation
            /// Get user confirmation where id & confirmation type is match
            $confirmationCheck = UserConfirmation::where([
                ["user_id", $user->id],
                ["confirmation_type", 1]
            ])->first();
            $successResponse = ["sign_key"=>$request->sign_key];
            if($confirmationCheck){
                $data = ["action_type"=>3];
                if($confirmationCheck->retry_count > 4) return $this->baseResponse->CustomResponse(false, 400, "Cannot send otp, too many atempt", $data);
                /// retry count
                switch ($confirmationCheck->retry_count) {
                    case 0:
                        /// 1 > 60
                        $seconds = 60;
                        break;
                    case 1:
                        /// 2 > 80
                        $seconds = 80;
                        break;
                    case 2:
                        /// 3 > 100
                        $seconds = 100;
                        break;
                    case 3:
                        /// 4 > 120
                        $seconds = 120;
                        break;
                    default:
                        $seconds = 60;
                        break;
                }
                $expiredAt = Carbon::createFromFormat("Y-m-d H:i:sO", $confirmationCheck->expired_at, new \DateTimeZone("Asia/Jakarta"));
                if($today->diffInSeconds($expiredAt, false)<=0){
                    $updateConfirmation = UserConfirmation::where("id", $confirmationCheck->id)
                        ->update([
                            "updated_at" => $today, 
                            "updated_by" => $confirmationCheck->user_id,
                            "access_key" => $otpCode,
                            "expired_at" => $today->addSeconds($seconds),
                            "retry_count" => $confirmationCheck->retry_count+1,
                            "is_used" => false,
                        ]);
                    if($updateConfirmation){
                        try {
                            $message = $twilio->messages
                            ->create("+".$user->phone_number, [
                                "body" => "(Confidential) Your verification code is ".$otpCode.". signature : ".$request->sign_key, 
                                "from" => $twilioNumber
                            ]);
                            if($message->status != "failed" || $message->status != "undelivered"){
                                DB::commit();
                                return $this->baseResponse->SuccessResponse(true, 200, "Otp successfully sended", $successResponse, 5);
                            }
                        } catch (\Exception $ex) {
                            DB::rollBack();
                            $data = ["action_type"=>1];
                            return $this->baseResponse->CustomResponse(false, 400, $ex->getMessage(), $data);
                        }
                    }
                }else{
                    $data = ["action_type"=>2];
                    return $this->baseResponse->CustomResponse(false, 400, "Cannot request otp, verification proses still on going", null);
                }
            }else{
                $userConfirmation = UserConfirmation::create([
                    "created_at" => $today, 
                    "created_by" => $user->id, 
                    "user_id" => $user->id,
                    "access_key" => $otpCode,
                    "confirmation_type" => 1,
                    "expired_at" => Carbon::now(new \DateTimeZone("Asia/Jakarta"))->addSeconds(60),
                ]);
                if($userConfirmation){
                    try {
                        $message = $twilio->messages
                        ->create("+".$user->phone_number, [
                            "body" => "(Confidential) Your verification code is ".$otpCode.". signature : ".$request->sign_key, 
                            "from" => $twilioNumber
                        ]);
                        if($message->status != "failed" || $message->status != "undelivered"){
                            DB::commit();
                            return $this->baseResponse->SuccessResponse(true, 200, "Otp successfully sended", $successResponse, 5);
                        }
                    } catch (\Exception $ex) {
                        DB::rollBack();
                        $data = ["action_type"=>1];
                        return $this->baseResponse->CustomResponse(false, 400, $ex->getMessage(), $data);
                    }
                }
            }
        }
        catch(\Exception $ex){
            DB::rollBack();
            return $this->baseResponse->InternalServerErrorResponse();
        }
    }
    
    /**
     * @OA\Post(
     *      path="/auth/login-otp",
     *      operationId="loginOtp",
     *      tags={"Auth"},
     *      @OA\RequestBody(
     *          required=true,
     *          description="Login-Otp payload",
     *          @OA\JsonContent(
     *              required={"access_key", "phone_number", "passsword"},
     *              @OA\Property(property="access_key", type="integer", example="12345"),
     *              @OA\Property(property="phone_number", type="string", example="6282115100216"),
     *              @OA\Property(property="password", type="string", example="123456"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response="200",
     *          description="Response description",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(property="status", type="string", description="Expected status", example=true),
     *                  @OA\Property(property="code", type="integer", description="Http code in the body", example=200),
     *                  @OA\Property(property="message", type="string", description="Message", example="Login successful"),
     *                  @OA\Property(property="data", type="object", description="Oauth data",ref="#/components/schemas/OauthToken")
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response="400",
     *          description="Error: Bad request. When expected parameters were not supplied.",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  allOf={
     *                      @OA\Schema(ref="#/components/schemas/BadRequest"),
     *                      @OA\Schema(
     *                          @OA\Property(property="error_message", type="object", description="Error Message",
     *                              @OA\Property(property="access_key", type="array",
     *                                  @OA\Items(type="string", example="Access key cannot empty"),
     *                              ),
     *                              @OA\Property(property="phone_number", type="array",
     *                                  @OA\Items(type="string", example="Phone key cannot empty"),
     *                              ),
     *                              @OA\Property(property="password", type="array",
     *                                  @OA\Items(type="string", example="Password key cannot empty"),
     *                              ),
     *                          ),
     *                      ),
     *                  },
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response="401",
     *          description="Error: Unauthorized. When rest-api client not authorized to use this API",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/Unauthorized"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response="403",
     *          description="Error: Forbiden access. When user doesn't have permission to access this API.",
     *          @OA\MediaType(
     *               mediaType="application/json",
     *               @OA\Schema(ref="#/components/schemas/ForbidenAccess"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response="404",
     *          description="Error: Account not found. Server cannot find the right user",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(property="status", type="string", description="Expected status", example=false),
     *                  @OA\Property(property="code", type="integer", description="Http code in the body", example=404),
     *                  @OA\Property(property="message", type="string", description="Message", example="Account not found"),
     *              )
     *          )
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

    public function LoginOtp(Request $request){
        try{
            $validator = Validator::make($request->all() , [
                "access_key" => "required", 
                "phone_number" => "required", 
                "password" => "required", ], [
                    "access_key.required" => "Access key cannot empty", 
                    "phone_number.required" => "Phone Number cannot empty", 
                    "password.required" => "Password cannot empty", 
                ]);
            if ($validator->fails()) return $this->baseResponse->BadRequestResponse($validator->errors());
            DB::beginTransaction();
            $today = Carbon::now(new \DateTimeZone("Asia/Jakarta"));
            $user = User::where("phone_number", $request->phone_number)->first();
            if(!$user)return $this->baseResponse->NotFoundResponse("Invalid phone number, no user found");
            if (Hash::check($request->password, $user->password)){
                $userConfirmation = UserConfirmation::where([
                    ["user_id", $user->id],
                    ["confirmation_type", 1],
                    ["access_key", $request->access_key],
                ])->first();
                if(!$userConfirmation) return $this->baseResponse->CustomResponse(false, 400, "Otp code is invalid", null);
                $expiredAt = Carbon::createFromFormat('Y-m-d H:i:sO', $userConfirmation->expired_at, new \DateTimeZone("Asia/Jakarta"));
                if($today->diffInSeconds($expiredAt, false)>0){
                    $updateConfirmation = UserConfirmation::where("id", $userConfirmation->id)
                    ->update([
                        "updated_at" => $today, 
                        "updated_by" => $userConfirmation->user_id,
                        "is_used"=> true,
                        "used_at"=> $today,
                        "retry_count"=> 0,
                    ]);
                    if($updateConfirmation){
                        if($user->is_active){
                            DB::commit();
                            $response = $this->getToken($user->email, $request->password);
                            return $response;
                        }
                        $updateUser = User::where("id", $user->id)
                        ->update([
                            "updated_at" => $today,
                            "updated_by" => $user->id,
                            "is_active" => true,
                        ]);
                        if($updateUser){
                            $response = $this->getToken($user->email, $request->password);
                            if($response["status"]) DB::commit();
                            else DB::rollBack();
                            return $response;
                        }
                        DB::rollBack();
                        return $this->baseResponse->CustomResponse(false, 400, "Failed to activate user", null);
                    }
                    DB::rollBack();
                    return $this->baseResponse->CustomResponse(false, 400, "Otp code is invalid", null);
                }
                return $this->baseResponse->CustomResponse(false, 400, "Otp code is expired", null);
            }
            return $this->baseResponse->UnauthorizedResponse();
        }
        catch(\Exception $ex){
            DB::rollBack();
            return $this->baseResponse->InternalServerErrorResponse();
        }
    }

    public function getToken(String $email, String $password){
        $postRequest = [
            "grant_type" => "password", 
            "client_id" => "2", 
            "client_secret" => "KTiytmYX8Lr829AD0L3CH2L6hq4S2yPwA4dlIj6l", 
            "username" => $email, 
            "password" => $password, 
            "scope" => '*'
        ];
        $tokenRequest = Request::create(env('APP_URL') . '/oauth/token', 'post', $postRequest);
        $response = app()->handle($tokenRequest);
        if ($response->getStatusCode() != 200) return $this->baseResponse->CustomArray(false, 400, "Unauthorized", null);
        $response = json_decode($response->getContent());
        return $this->baseResponse->CustomArray(true, 200, "Login sucess", $response);
    }
}

