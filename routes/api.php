<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

use GatewayClient\Gateway;

Route::get('/send', function (Request $request) {
    Gateway::$registerAddress = '127.0.0.1:12360';
    Gateway::sendToClient($request->get('client_id'), json_encode(['data' => 'Hello World!', 'type' => 'message']));
    // Gateway::sendToAll(json_encode(['data' => 'Hello World!', 'type' => 'message']));
});

Route::post('/bind', function (\Illuminate\Http\Request $request) {
    Gateway::$registerAddress = '127.0.0.1:12360';

    $clientId = $request->get('client_id');

    Gateway::bindUid($clientId, 1);

    Gateway::sendToUid(1, json_encode(['data' => 'Bind successful send', 'type' => 'message']));

    return response()->json(['data' => 'Bind successful return', 'type' => 'message'], 200);
});
