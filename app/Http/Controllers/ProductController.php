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
    public function getAllProducts(string $search)
    {
        $searchByName = Product::where('title', 'like', "%" . $search . "%")->orWhere('brands', 'like', "%" . $search . "%")->get();
        $searchByCode = Product::where('codebar', '=', $search)->get();

        if (preg_match("/^\d+$/", $search)) {
            return count($searchByCode) > 0 ? $searchByCode : $this->getDataByCode($search);
        }else{
            return count($searchByName) > 0 ? $searchByName : $this->getDataByName($search);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getDataByName(string $search)
    {
        $response = Http::get( 'https://fr.openfoodfacts.org/cgi/search.pl?action=process&search_terms='.$search.'&json=true' );
        $data = $response->json();

        $products=[];

        foreach ($data['products'] as $item) {

            $product = new Product;

            if(isset($item['image_small_url'])){
                $url = $item['image_small_url'];
                $info = pathinfo($url);
                $contents = file_get_contents($url);
                $file = storage_path("images\products\product_") . $item['code'] . '.' . $info['extension'];
                file_put_contents($file, $contents);
            }

            $newProduct = $product->firstOrCreate([
                'codebar' => $item['code'],
                'title' => $item['product_name'],
                'brands' => $item['brands'],
                'nutriScore' => isset( $item['nutrition_grades'] ) ? $item['nutrition_grades'] : null,
                'novaScore' => isset( $item['nova_group'] ) ? $item['nova_group'] : null,
                'image' => isset( $item['image_small_url'] ) ? $file : null,
            ]);

            array_push($products, $newProduct);
        }

        return $products;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function getDataByCode(string $codebar)
    {

        $response = Http::get( 'https://fr.openfoodfacts.org/api/v0/product/'.$codebar );
        $data = $response->json();
        $product = new Product;
        $item = $data['product'];


        if(isset($item['image_small_url'])){
            $url = $item['image_small_url'];
            $info = pathinfo($url);
            $contents = file_get_contents($url);
            $file = storage_path("images\products\product_") . $item['code'] . '.' . $info['extension'];
            file_put_contents($file, $contents);
        }



        $newProduct = $product->firstOrCreate([
            'codebar' => $item['code'],
            'title' => $item['product_name'],
            'brands' => $item['brands'],
            'nutriScore' => isset( $item['nutrition_grades'] ) ? $item['nutrition_grades'] : null,
            'novaScore' => isset( $item['nova_group'] ) ? $item['nova_group'] : null,
            'image' => isset( $item['image_small_url'] ) ? $file : null,
        ]);


        return $newProduct;

    }
}
