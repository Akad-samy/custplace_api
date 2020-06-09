<?php

namespace App\Http\Repositories;

use App\Ingredient;
use App\Jobs\storeProducts;
use App\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class ProductRepository implements ProductInterface
{

    public function all()
    {

        if (preg_match("/^\d+$/", request('search'))) {
            return $this->barcode(request('search'));
        } else {
            return $this->title(request('search'));
        }






        // $products = Product::where('title', 'like', "%" . request('search') . "%")
        //     ->orWhere('brand', 'like', "%" . request('search') . "%")
        //     ->orWhere('codebar', '=', request('search'))
        //     ->get();

        // $products->when(preg_match("/^\d+$/", request('search')), function ($p) {
        //     return count($p) > 0 ? $p : $this->codebar(request('search'));
        // }, function () {
        //     return $this->title(request('search'));
        // });

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function title(string $title)
    {

        //why this function is insiade braces
        dispatch(new storeProducts($title));



        sleep(5);

        $products = Product::where('title', 'like', "%" . $title  . "%")
            ->orWhere('brand', 'like', "%" . $title . "%")
            ->paginate(10);

        return $products;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function barcode(string $barcode)
    {

        $response = Http::get('https://fr.openfoodfacts.org/api/v0/product/' . $barcode);
        $data = $response->json();
        $product = new Product;
        $item = $data['product'];


        if (isset($item['image_small_url'])) {
            $url = $item['image_small_url'];
            $info = pathinfo($url);
            $contents = file_get_contents($url);
            $file = storage_path("images/products/product_") . $item['code'] . '.' . $info['extension'];
            file_put_contents($file, $contents);
        }



        $newProduct = $product->firstOrCreate([
            'codebar' => $item['code'],
            'title' => $item['product_name'],
            'brand' => isset($item['brands']) ? $item['brands'] : null,
            'nutri_score' => isset($item['nutrition_grades']) ? $item['nutrition_grades'] : null,
            'nova_group' => isset($item['nova_group']) ? $item['nova_group'] : null,
            'image' => isset($item['image_small_url']) ? $file : null,
        ]);


        return $newProduct;
    }
}
