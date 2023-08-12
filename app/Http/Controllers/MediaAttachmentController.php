<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BaseResponse;
use Carbon\Carbon;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Aws\S3\ObjectUploader;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MediaAttachmentController extends Controller
{

    private $baseResponse;
    public function __construct()
    {
        $this->baseResponse = new BaseResponse();
    }

    /**
     * @OA\Post(
     *     path="/media/upload",
     *     operationId="mediaUpload",
     *     tags={"Media"},
     *     security={{"token": {}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *         mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"file", "path"},
     *                 @OA\Property(
     *                     description="Attachment file", property="file", type="file", format="binary",
     *                 ),
     *                 @OA\Property(property="path", type="string", example="document/avatar"),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Response description",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="status", type="string", description="Expected status", example=true),
     *                 @OA\Property(property="code", type="integer", description="Http code in the body", example=200),
     *                 @OA\Property(property="message", type="string", description="Message", example="Registrasi berhasil"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Error: Bad request. When expected parameters were not supplied.",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 allOf={
     *                     @OA\Schema(ref="#/components/schemas/BadRequest"),
     *                     @OA\Schema(
     *                         @OA\Property(property="error_message", type="object", description="Error Message",
     *                             @OA\Property(property="file", type="array",
     *                                 @OA\Items(type="string", example="Please attach a file to upload"),
     *                             ),
     *                         ),
     *                     ),
     *                 },
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Error: Unauthorized. When rest-api client not authorized to use this API",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/Unauthorized"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="403",
     *         description="Error: Forbiden access. When user doesn't have permission to access this API.",
     *         @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/ForbidenAccess"),
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

    public function Upload(Request $request){
        $validator = Validator::make($request->all() , [
            "file" => "required|mimes:jpeg,jpg,png,pdf|max:2000",
            "path" => "required",
            ], [
                "path.required" => "Path is mandatory",
                "file.required" => "Please attach a file to upload", 
                "file.mimes" => "Only jpeg, jpg, png & pdf are allowed", 
                "file.max" => "File cannot more than 2Mb", 
            ]);
        if ($validator->fails()) return $this->baseResponse->BadRequestResponse($validator->errors());
        try {
            if ($request->hasFile('file')) {
                $random = Str::random(10);
                $today = Carbon::now(new \DateTimeZone("Asia/Jakarta"));
                $bucket = getenv("AWS_BUCKET");
                $region = getenv("AWS_REGION");
                $extension = $request->file('file')->extension();
                $s3 = new S3Client([
                    'version' => 'latest',
                    'region' => $region
                ]);
                $basePath = $request->path."/";
                $fileName = $random.preg_replace('/\\s+|-+|_+|:/', '', $today.".".$extension);
                $filePath = $basePath.auth()->user()->storage_id."/".$fileName;
                $uploader = new ObjectUploader($s3,$bucket,$today,$request->file('file'));
                $upload = $s3->putObject(array(
                    'Bucket'     => $bucket,
                    'Key'        => $filePath,
                    'SourceFile' => $request->file('file'),
                ));
                $result = $uploader->upload();
                if($result){
                    $serverPath = "https://".$bucket.".s3-".$region.".amazonaws.com/".$filePath;
                    $response = ["url" => $serverPath];
                    return $this->baseResponse->SuccessResponse(true, 200, "Uploaded", $response, 5);
                }
            }
            return $this->baseResponse->InternalServerErrorResponse();
        } catch (\Exception $ex) {
            return $this->baseResponse->InternalServerErrorResponse();
        }
    }
}