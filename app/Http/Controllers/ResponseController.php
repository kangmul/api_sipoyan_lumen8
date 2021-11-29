<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class ResponseController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Respon dengan class Object Illuminate\HTTP\Response
     */

    public function response()
    {
        $data = [
            'status' => 'Success',
            'code' => 200,
            'message' => 'this is the response for your request',
        ];
        return (new Response($data, 200))
            ->header('Content-Type', 'application/json');
    }

    public function response2()
    {
        $data = [
            'status' => 'Success',
            'code' => 200,
            'message' => 'Response ini dengan menggunakan response class object',
        ];

        return response()->json([
            'message' => 'This is the response',
            'status' => 'Success',
        ], '200');
    }
}
