<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $curl = curl_init();

        // curl_setopt_array($curl, array(
        // CURLOPT_URL => "https://world.openfoodfacts.org/api/v0/product/737628064502.json",
        // CURLOPT_RETURNTRANSFER => true,
        // CURLOPT_TIMEOUT => 30,
        // CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        // CURLOPT_CUSTOMREQUEST => "GET",
        // CURLOPT_HTTPHEADER => array(
        //     "cache-control: no-cache"
        // ),
        // ));

        // $response = curl_exec($curl);
        $response = Http::get( 'https://world.openfoodfacts.org/api/v0/product/737628064502.json' );

        // Array of data from the JSON response
        $data = $response->json();
        return $data['product'];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product = new Product;

        $product->codebar = $request->codebar;
        $product->title = $request->title;
        $product->nutriScore = $request->nutriScore;
        $product->novaScore = $request->novaScore;

        $url = $request->image;
        $info = pathinfo($url);
        $contents = file_get_contents($url);
        $file = storage_path("images\products\\ ") . $product->codebar . '.' . $info['extension'];
        file_put_contents($file, $contents);
        $uploaded_file = new UploadedFile($file, $info['basename']);
        // dd($uploaded_file);
        $product->image = $file;
        $product->save();

        return response()->json($product);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
