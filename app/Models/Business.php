<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Business extends Model{
    /**
     *  @OA\Schema(
     *      schema="GetBusiness",
     *      allOf={
     *          @OA\Schema(
     *              @OA\Property(property="id", type="integer", description="Record id", example="1"),
     *              @OA\Property(property="name", type="string", description="Business name", example= "Saysweet"),
     *              @OA\Property(property="address", type="string", description="Business address", example="Sayati, Kec. Margahayu, Bandung, Jawa Barat 40228"),
     *              @OA\Property(property="business_type_id", type="integer", description="Business type id", example=1),
     *              @OA\Property(property="latitude", type="number", description="Latitude coordinate", example=-6.9754222),
     *              @OA\Property(property="longitude", type="number", description="Longitude coordinate", example=107.577356),
     *              @OA\Property(property="tax_name", type="string", description="Tax name", example="Dirgham Ashauqi Zabir"),
     *              @OA\Property(property="tax_number", type="string", description="Business tax number", example="86.334.862.9-445.000"),
     *              @OA\Property(property="attachment_url", type="string", description="Tax card attachment (NPWP)", example="https://lh5.googleusercontent.com/p/AF1QipPBsvuGf1298eauB-01hzB96dplSHz0c-HquS0q=w408-h408-k-no"),
     *              @OA\Property(property="is_verified", type="boolean", description="Verified or not flag", example=false),
     *              @OA\Property(property="phone_number", type="string", description="Business phone number (HQ)", example="6282115100216"),
     *              @OA\Property(property="email", type="string", description="Business e-mail (HQ)", example="admin@saysweet.com"),
     *              @OA\Property(property="postal_code", type="integer", description="Postal code", example=40378),
     *          ),
     *          @OA\Schema(
     *              @OA\Property(property="regional", type="array", description="Regional data",
     *                  @OA\Items(ref="#/components/schemas/GetRegional"),
     *              ),
     *          ),
     *      },
     * ),
     */
    protected $table = 'business';
    protected $fillable = [
        'id' ,
        'created_at', 
        'created_by', 
        'updated_at', 
        'updated_by', 
        'owner_id', 
        'name', 
        'address', 
        'business_type_id', 
        'sub_district_id',
        'latitude', 
        'longitude',
        'tax_name', 
        'tax_number', 
        'attachment_url',
        'is_deleted',
        'is_verified',
        'phone_number',
        'email',
        'postal_code',
    ];

    protected $hidden = [
        'created_at', 
        'created_by', 
        'updated_at', 
        'updated_by', 
        'owner_id', 
        'sub_district_id',
        'is_deleted',
    ];

    public function regional()
    {
        return $this->belongsTo('App\Models\Regional', 'sub_district_id', 'sub_district_id');
    }
}
