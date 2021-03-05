<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreImagesRequest;
use App\Modules\Requester as GuzzleRequester;

class ImagesController extends Controller
{
    /**
     * Used to request data using Guzzle
     * @var GuzzleRequester $requester 
     */
    private $requester;

    function __construct()
    {
        $this->requester = new GuzzleRequester;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreImagesRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreImagesRequest $request)
    {
        $uploaded = [];
        foreach ($request->images as $b64file) {
            $response = $this->requester->post(config("external.image_upload"), ["field" => "imageData", "value" => preg_split("/[,]/",  $b64file)[1]]);
            if ($response->status != "success") return response()->json(null, 500);
            array_push($uploaded, $response->url);
        }
        if (sizeof($uploaded) > 0) return response()->json(["images" => $uploaded], 201);
        return response()->json(null, 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
