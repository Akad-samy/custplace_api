<?php

namespace App\Repositories;

use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Interfaces\IngredientInterface;
use App\Interfaces\OriginalStoreInterface;
use App\Interfaces\ProductInterface;
use App\Jobs\StoreProducts;
use App\Product;
use Exception;

class ProductRepository implements ProductInterface
{
    protected $originalData;
    protected $ingredients;

    public function __construct(OriginalStoreInterface $originalData, IngredientInterface $ingredients)
    {
        $this->originalData = $originalData;
        $this->ingredients = $ingredients;
    }


    public function all($product, $page_size = null, $page = null)
    {

        if (preg_match("/^\d+$/", $product)) {
            return $this->getByBarcode($product);
        } else {
            return $this->getByTitle($product, $page_size, $page);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response.
     *
     */
    public function getByTitle(string $title, $page_size, $page)
    {

        $myData =  Product::where('title', 'like', "%" . $title . "%")
            ->orWhere('brand', 'like', "%" . $title . "%")
            ->with('ingredients:label')
            ->paginate($page_size, ['*'], 'page', $page);
        $products = $this->originalData->getByTitle($title, $page_size, $page);

        if (count($myData) < $page_size) {

            if(!isset($products['products'])){
                throw new Exception('OpenFoodFact server is not available');
            }
            dispatch(new StoreProducts($title, $page_size, $page));
            return $products;
        } else {
            return new ProductCollection($myData);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function getByBarcode(string $barcode)
    {
        $product = $this->originalData->getByBarcode($barcode);
        $data = Product::where('codebar', $barcode)->first();

    //     if ($data != null) {
    //         return new ProductResource($data);
    //     } else {
    //         if (!isset($product['status'])) {
    //             throw new Exception('OpenFoodFact server is not available');
    //         } else {
    //             $this->store($product['product']);
    //         }
    //         return $product;
    //     }
    // }

    if (!isset($product['status'])) {
        throw new Exception('OpenFoodFact server is not available');
    } else {
        $this->store($product['product']);
    }
    return $product;
}
    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function store($request)
    {
            $productIngredients = [];

            if (isset($request['image_small_url'])) {
                $url = $request['image_small_url'];
                $info = pathinfo($url);
                $contents = file_get_contents($url);
                //the line below wwill store images inside Storage folder
                // $file = storage_path("images\products\product_") . $request['code'] . '.' . $info['extension'];
                $file = base_path("public/images/products/product_") . $request['code'] . '.' . $info['extension'];
                file_put_contents($file, $contents);
            }

            if (isset($request['ingredients'])) {
                $productIngredients = $this->ingredients->store($request['ingredients']);
            }

            $data = Product::firstOrCreate([
                'codebar' => $request['code'],
                'title' => isset($request['product_name']) ? $request['product_name'] : '',
                'brand' => isset($request['brands']) ? $request['brands'] : '',
                'nutri_score' => isset($request['nutrition_grades']) ? $request['nutrition_grades'] : '',
                'nova_group' => isset($request['nova_group']) ? $request['nova_group'] : '',
                'image' => isset($request['image_small_url']) ? "/product_" . $request['code'] . '.' . $info['extension']: '',
            ]);
            $data->ingredients()->saveMany($productIngredients);
    }
}
