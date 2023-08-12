<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Header extends Model
{
/**
 * @OA\SecurityScheme(
 *   securityScheme="token",
 *   type="apiKey",
 *   name="Authorization",
 *   in="header"
 * )
 */
}