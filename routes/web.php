<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
    
$router->get('/', function () use ($router) {
    return $router->app->version();
});
$router->group(['middleware' => 'auth'], function () use ($router) {
    $router->put('auth/profile', 'UserController@UpdateProfile');
    $router->get('auth/profile', 'UserController@Profile');
    /// Categories
    $router->get('categories', 'CategoriesController@Select');
    $router->post('categories', 'CategoriesController@Create');
    /// Business
    $router->get('business', 'BusinessController@Select');
    $router->post('business', 'BusinessController@Create');
    $router->put('business/{id}', 'BusinessController@Update');
    $router->delete('business/{id}', 'BusinessController@Delete');
    /// Business type
    $router->post('business_type', 'BusinessTypeController@Create');
    /// Outlet
    $router->get('outlet', 'OutletController@Select');
    $router->post('outlet', 'OutletController@Create');
    $router->put('outlet/{id}', 'OutletController@Update');
    $router->delete('outlet/{id}', 'OutletController@Delete');
    $router->post('media/upload', 'MediaAttachmentController@Upload');
    /// Item
    $router->get('item', 'ItemController@Select');
    $router->post('item', 'ItemController@Create');
    /// Purchase Order
    $router->get('purchase_order', 'PurchaseOrderController@Select');
    $router->post('purchase_order', 'PurchaseOrderController@Create');
    /// Item
    $router->get('supplier', 'SupplierController@Select');
    $router->post('supplier', 'SupplierController@Create');
    /// Stock
    $router->post('stock', 'StockController@Create');
});

$router->get('regional', 'RegionalController@Select');
$router->get('business_type', 'BusinessTypeController@Select');
$router->post('auth/send-otp', 'UserController@SendOtp');
$router->post('auth/login-otp', 'UserController@LoginOtp');
$router->post('auth/register', 'UserController@Register');
$router->post('auth/login', 'UserController@Login');