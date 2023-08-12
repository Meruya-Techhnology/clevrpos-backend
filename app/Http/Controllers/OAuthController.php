<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OAuthController extends Controller
{
    /// Generate Token from Lumens Passpor
    /// by calling path /oauth/token
    /// method POST
     /**
     * @OA\Post(
     *     path="/oauth/token",
     *     operationId="/oauth/token",
     *     tags={"OAuth"},
     *  @OA\RequestBody(
     *     required=true,
     *     description="Pass user credentials",
     *     @OA\JsonContent(
     *         required={"grant_type","scope","client_id","client_secret"},
     *             @OA\Property(property="grant_type", type="string", example="client_credentials/password"),
     *             @OA\Property(property="client_id", type="int", example="2"),
     *             @OA\Property(property="client_secret", type="string", example="KTiytmYX8Lr829AD0L3CH2L6hq4S2yPwA4dlIj6l"),
     *             @OA\Property(property="username", type="string", example="dwikurnianto"),
     *             @OA\Property(property="password", type="string", format="password", example="123456"),
     *             @OA\Property(property="scope", type="string", example="*"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Success generating token",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="token_type", type="string", description="Authorization token type", example="Bearer"),
     *                 @OA\Property(property="expires_in", type="string", description="Expired date of token (Unix Timestamp)", example="31536000"),
     *                 @OA\Property(property="access_token", type="string", description="Access token", example= "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiNDMzMmE4ZGU5NWVhZGViMzYxMGZlZWI4MWE1Zjg2YjMzNzcwZTJhMjEzYjRjODQ5MjVkMDY5NmZkZjVjNmQ3ZmM4NTEzODMyMGFiYTQ2YTgiLCJpYXQiOiIxNjExNjIyNjUwLjE3NDg1NiIsIm5iZiI6IjE2MTE2MjI2NTAuMTc0ODYyIiwiZXhwIjoiMTY0MzE1ODY0OS44MTExODAiLCJzdWIiOiIiLCJzY29wZXMiOlsiKiJdfQ.1I5FgO5rzhaMm1MFksXJIjnDcViYw4Hs5BOM2bOyYRVyRd1j_CyyyEWtW7gkUI_iV0cburraIMDaTBpEkm6y6HYZBDgTyqV3aHL7KY-aqrRGuPhI35dKYQisESU8P4JJvI8DNIcZ5qbG6OH3SmO7coZ0GF5DDXzRaZ84qENNqrQ2M_-YSeA22W-YYYUVcu9ccXOZ5lR4kH7p4QmYMdrIE8nRfmO_DNL9GFamIQ69SPW7OuqRdkZG2qJInE8F5AoH9xB10kG2q2FFAt-0fUULgbILfvo4QTyPRszXLm7Xuo54WmJaTvzbh82wUcNdKekgaIZzoCYj2BE5ky4ckj3jhSZ5H0yT8FEOcZ7BRqb4GwAYxrGYbW-Q01CNuD9ayncm3RnvwZkvUflY3bTJ6he7ArNQGDTJZoHzMHUmU31ZgGZixousBZxF4z1AYjEF20MwI64hnzdiycICSnxGJnieMptAP80e-5POa1eSWdfUt5bn9aklp_vNS_hs05aGfVo4p5Sl4BWv-UoxmrfX1uNH-Er37FBnOmovjuvai4uLA5mAHz-QCLTylg0815piVypsOkb9lIpp33M0l-kkdmmyXeIKoR0hAco8w9V0RDP9FqXnYOOvGKOye1tTQUc9_F28gBMD_voMhRHoAlZJ-ZAQn-pTudcgQPd3eX9QnqKgcRo"),
     *                 @OA\Property(property="refresh_token", type="string", description="Refresh token", example="def502009707e2dda2e43603f4f75fb1763fd7fcd315861e821181db796cf276828c0822432a8f466538fab10781ec2c6d3ee2f8c8277d0fc402b51312052ff7778a46ad102ffcf2de935cd464a0d677ff217d26f5405d86fa03d84d0f2c4b8d64a5145a57fdb48bf1761d46d2dc71e29ac92a8514e1284ab9ea1fd929779ecef8aa982f13d5fb052a3e298161525e0cd6922ea3cd68d3b8edaa14d2fba6da60e801f16215afc000bffcbde95969302d6717b57e0dcfe8caa1e0e96c868de3c99642c095371493993945c99faf702b7194464b8bf0a41d77a8717d04e9615e939c147ab2f6084f712dc132870a849f5331519de9e2d7c4b7512782341d9505b3b490878d0ffc1e43956f6831f16f04acfe6220415b66a387e3c7edd3c66c44010b7f641348bb3ab63b884f78a38745401ca3393e4ef1d1b0c91035526190f0ae6cffbf6aded2955dd10336be506e5c1be3a5df6234ecd4d7b28e5ee24895c2f4efda1b91"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Bad request. When required parameters were not supplied.",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="error", type="string", description=""),
     *                 @OA\Property(property="error_description", type="string", description=""),
     *                 @OA\Property(property="hint", type="string", description=""),
     *                 @OA\Property(property="message", type="string", description=""),
     *                 example={
     *                     "error": "invalid_request",
     *                     "error_description": "The request is missing a required parameter, includes an invalid parameter value, includes a parameter more than once, or is otherwise malformed.",
     *                     "hint": "Check the `client_id` parameter",
     *                     "message": "The request is missing a required parameter, includes an invalid parameter value, includes a parameter more than once, or is otherwise malformed."
     *                 }
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Wrong client secret",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="error", type="string", description=""),
     *                 @OA\Property(property="error_description", type="string", description=""),
     *                 @OA\Property(property="message", type="string", description=""),
     *                 example={
     *                     "error": "invalid_client",
     *                     "error_description": "Client authentication failed",
     *                     "message": "Client authentication failed"
     *                 }
     *             )
     *         )
     *     ),
     *      @OA\Response(
     *          response="403",
     *          description="Error: Bad request. When required parameters were not supplied.",
     *          @OA\MediaType(
     *               mediaType="application/json",
     *               @OA\Schema(ref="#/components/schemas/Unauthorized"),
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

    /**
     * @OAS\Schema(
     *    type="object",
     *    description="Token model",
     * )
     */

    /**
     * @OAS\Property(property="grant_type",type="string",example="Bearer")
     * @OAS\Property(property="expires_in",type=int,example=31536000)
     * @OAS\Property(property="access_token",type="string",example="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiMzI2YWY5Y2ExOTRmMzM4Mjk2ZWU4MzVmYTlkZDJmZjMzZGE3OGI2YTY3MTRhMDc2MTZmMGViNmMxOTg5MTViOThjZTI2ZDRlNWU1ODdkMTMiLCJpYXQiOiIxNjExNTQzMjg3LjIyMTM5NyIsIm5iZiI6IjE2MTE1NDMyODcuMjIxNDAzIiwiZXhwIjoiMTY0MzA3OTI4Ny4xMDU1NTEiLCJzdWIiOiIiLCJzY29wZXMiOlsiKiJdfQ.jLDs0D_hAD-sEKyvMRh1Xj6NOKK4QMxYZ6wSXu9M5usXx0COLlbCtYfDUhyTTTO9SZpDE_fl9kWVz1OToSKsZ1wIIEAb-6nXBjyex2pQkFZWNGuQoESdLBaOxLf4SVFY8qengk9Le3irrkrLSyLfXXmUBH4wZvMkMV-Ov0lMfxttnwX9UtW4Qk4k8MlKqHV1jEg9meROVhX-Ey7CahflmD0Ld3VsCJQ8ghz6rzh7UHmWuhB4HPzI82JGCkGDkzOrJi6uPharroAKrkZr_RhrYS60ACfSHyNmy2yl-1lxp74IJHJls3g1Y_2MVxco1JdCAK5RPusp4BfZx6CldgkgtypUt5GxGI5pGToAsfCKNtoNu08XqL_OdGSfwLSCg6WGGvgPb3QnhMNN_hufegsJzXYnsJ9fX07JinAk42Eo8c8gOAw9RYauDWlQFWO0bfPwRnvVR1aY03VVGcKd9Vg3tMAnAl5qbvwL-nEIPwHFbYRmY7cVP9SPM-qWuUVpDfcD0iJSRRJpb5GGGW4ggFkQ05133UHi0ccAwIpAQ8wZ6QaW06wqNRqR7WZ-GFFPp2HmB45qXR1xqUQSif4MDyvPJUe4w9OmdImvei-eR_Cd-TBBOfY6TAUIX6Z-Sd4DiV4yE0yNGrn9Ntf-PNT9bXefCTyTlv5pZIx4Jv0YHYDDFbA")
     *
     * @return array
     */

    
    /// Get Active User(Password) Token from Lumens Passpor
    /// by calling path /oauth/tokens
    /// method GET
    /**
     * @OA\Get(
     *     path="/oauth/tokens",
     *     operationId="/oauth/tokens",
     *     tags={"OAuth"},
     *     security={
     *         {
     *             "token": {}
     *         }
     *     },
     *     @OA\Response(
     *         response="200",
     *         description="Success generating token",
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Bad request. When required parameters were not supplied.",
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Wrong client secret",
     *     ),
     *     @OA\Response(
     *         response="403",
     *         description="Forbiden access. When rest-api client isn't authorized to use this API",
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Error: Internal server error. This is happen when server had internal error",
     *     ),
     * )
     */

    /// Delete active token by using token id
    /// method Delete

    /**
     * @OA\Delete(
     *     path="/oauth/tokens/{token_id}",
     *     operationId="/oauth/token/{token_id}",
     *     tags={"OAuth"},
     *     @OA\Parameter(
     *         name="token_id",
     *         in="path",
     *         description="token id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Success generating token",
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Bad request. When required parameters were not supplied.",
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response="403",
     *         description="Forbiden access. When rest-api client isn't authorized to use this API",
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Error: Internal server error. This is happen when server had internal error",
     *     ),
     * )
     */


     /// Get Client
     /// Method GET

    /**
     * @OA\Get(
     *     path="/oauth/clients",
     *     operationId="/oauth/clients",
     *     tags={"OAuth"},
     *     security={
     *         {
     *             "token": {}
     *         }
     *     },
     *     @OA\Response(
     *         response="200",
     *         description="Success generating token",
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Bad request. When required parameters were not supplied.",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/InternalServerError"),
     *         )
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Wrong client secret",
     *         @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/ForbidenAccess"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="403",
     *         description="Error: Bad request. When required parameters were not supplied.",
     *         @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/Unauthorized"),
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
}