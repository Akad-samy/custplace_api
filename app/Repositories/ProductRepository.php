<?php

namespace App\Repositories;

use App\Interfaces\IngredientInterface;
use App\Interfaces\OriginalStoreInterface;
use App\Interfaces\ProductInterface;
use App\Jobs\StoreProducts;
use App\Product;

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
            return $this->barcode($product);
        } else {
            return $this->title($product, $page_size, $page);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response.
     *
     */
    public function title(string $title, $page_size, $page)
    {

        $myData =  Product::where('title', 'like', "%" . $title . "%")
            ->orWhere('brand', 'like', "%" . $title . "%")
            ->with('ingredients:label')
            ->paginate($page_size, ['*'], 'page', $page);

        $items = [];

        if (count($myData) < $page_size) {

            dispatch(new StoreProducts($title, $page_size, $page));

            $products = $this->originalData->title($title, $page_size, $page);
            foreach ($products['products'] as $product) {
                $items[] = $product;
            }

        } else {
            foreach ($myData->items() as $item) {
                $items[] = $this->format($item);
            }
        }
        return $items;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function barcode(string $barcode)
    {
        $product = $this->originalData->barcode($barcode);
        $data = Product::where('codebar', $barcode)->first();


        if($data != null) {
            return $this->format($data);
        }else {
            $this->store($product);
            return $product;
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function store($product)
    {

        $productIngredients = [];

        if (isset($product['image'])) {
            $url = $product['image'];
            $info = pathinfo($url);
            $contents = file_get_contents($url);
            $file = storage_path("images\products\product_") . $product['codebar'] . '.' . $info['extension'];
            file_put_contents($file, $contents);
        }

        if (isset($product['ingredients'])) {
            $productIngredients = $this->ingredients->store($product['ingredients']);
        }

        $data = Product::firstOrCreate([
            'codebar' => $product['codebar'],
            'title' => isset($product['title']) ? $product['title'] : '',
            'brand' => isset($product['brand']) ? $product['brand'] : '',
            'nutri_score' => isset($product['nutri_score']) ? $product['nutri_score'] : '',
            'nova_group' => isset($product['nova_group']) ? $product['nova_group'] : '',
            'image' => isset($product['image']) ? $file : '',
        ]);
        $data->ingredients()->saveMany($productIngredients);


        return [
            "product" => $data,
            "ingredients" => $productIngredients,
        ];
    }

    public function format($data) {
        $ingredients = [];
        if (isset($data['ingredients'])) {
            foreach ($data['ingredients'] as $i) {
                $ingredients[] = $i['label'];
            }
        }
        $product = [
            'codebar' => $data['codebar'],
            'title' => $data['title'],
            'brand' => $data['brand'],
            'nutri_score' => $data['nutri_score'],
            'nova_group' => $data['nova_group'],
            'image' => $data['image'],
            'ingredients' => $ingredients,
        ];
        return $items[] = $product;
    }
}
