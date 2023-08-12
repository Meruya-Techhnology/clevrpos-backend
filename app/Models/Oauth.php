<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Oauth extends Model
{

    
    /**
     *  @OA\Schema(
     *      schema="OauthToken",
     *      allOf={
     *          @OA\Schema(
     *                 @OA\Property(property="token_type", type="string", description="Authorization token type", example="Bearer"),
     *                 @OA\Property(property="expires_in", type="integer", description="Expired date of token (Unix Timestamp)", example=31536000),
     *                 @OA\Property(property="access_token", type="string", description="Access token", example= "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIyIiwianRpIjoiOWFlYTIwOWI5ZGE3ODk1ODdlMWFkMzM1ZmEzYjJkODE3OTg1YzEzZGQ0MDFlMzdjOGI1MzU3ZDgwNjg5OGJhYjcyZGI1NmNkZjVlZWFlMjkiLCJpYXQiOiIxNjEyNTg4NzcyLjQxNTQ3OCIsIm5iZiI6IjE2MTI1ODg3NzIuNDE1NDgyIiwiZXhwIjoiMTY0NDEyNDc3Mi4zODM4MzEiLCJzdWIiOiIxIiwic2NvcGVzIjpbIioiXX0.VHOpbJM5-y8Ay20ri7EXStEfud4SLClMJffvK685YDQQMb5CaQc_9CB6Lzw6SdE5RQ25VJ0vGUzOMhp5eLpE99vZOnllCfnVy1m2W_W1br-z5oqXEbsfJHgdFuPCbDihYRBXM61WP9poeJSDv9XXcq6utRrJpeiGO0YBYzwjQigAwdux6pN3j84A47vvyYzcgRWzO5IE_2fQUMGDATdc9tFKv8rP5MBV1UmCi6Mn2AVrleC2Dl1PYRVSXqu6hsxDxbmwyAa2bSOnDhPBohV3QJdslGWMQ-VTDod07VOwIpiv_eOOest4-xz3vn7rIGnmbKdy5TqEn4JqeKW46Nd4NeR8yip8UboEqLclg0GEn2SHKji_YwGHwPVChxpoV2fJjGCdP2g7zC7gNBW89WtYgg4vDUhLNQYI-kq_yMuAkS8pIkwb0Iivf0-Qo8AEoSiVztgkOOMO0qBvItJYxjPP_40diYNiIkKRI2A-JB6NroARUsQoaNofyp7d1SPzvrMKb1lvA5dvdRC9ZQXkH0lykGaJ_PQCBqw1xK5fQIaE36O8RDhRcG1yXgDgFFXMrRTRyRNgvyZvqLXCofUnbMeoDWvvkYfR20nRLtuWVFBrHsWjYGWXksIefuYJz2NjC3b_iYkxnH1WWOUwSFUcDRiEijPS4OuSP3zHikg-7YTPKCM"),
     *                 @OA\Property(property="refresh_token", type="string", description="Refresh token", example="def502009d42af43d55c7f8ee2d807fee1e6384fbdbbf19043cbd29cebf1a93133782f10a8b7178140815ed8defb1144383e54b86d676600024da24e6cb44b41608dc8a346d2285899d82f233457ab23eadaa51ae88e729ce7747ee8890886ee348bd34faa57ce2ecdc1af88548bbb3c95f9143cd32eb95e4cfb619ff9fb5019b59a0a58cf39ac6a301278c4e7c826d8eed5f6a61193d4613a85f1a971b201e9141d8cbff582d79616683a792924a6b2dcb2d7088f746e6fb6e653bba911517a217312ee125bd0af1bd49ec1634873f0201c99233cc3e0fa9a19e5f4de802d64b312720d0a66f027434a7e6838d6a3af66dc47b9f42d787594ab17fe647f505c0f50114086287fa4740c2b196d8235c2cc5f1111e52aa588183cbc3915f8088ce30ce566add3feee33136a096df786b868bde6039f0c56e1b3a141c0c3961bbf0be231d445153e970ded87d14c4a032ef8f7c9ced2bac73b4f7e42ebddc9f22e3a8d78ab"),
     *             ),
     *      },
     * ),
     */
    
    /**
     * Remove comment to use variable bellow
     *
     * @var String
     */
    // protected $table = 'user_confirmation';
    // protected $connection = 'mysql';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        ///Write fillable table field here
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        ///Write hidden table field here
    ];
}