<?php

namespace App\Jobs;

use App\Product;

class storeProducts extends Job
{

    /**
     * @var array
     */
    private $items;

    /**
     * @var array
     */
    private $products;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $items, array $products)
    {
        $this->items = $items;
        $this->products = $products;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {


        foreach ($this->items as $item) {

            $product = new Product();

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
                'brands' => isset( $item['brands'] ) ? $item['brands'] : '',
                'nutriScore' => isset( $item['nutrition_grades'] ) ? $item['nutrition_grades'] : '',
                'novaScore' => isset( $item['nova_group'] ) ? $item['nova_group'] : '',
                'image' => isset( $item['image_small_url'] ) ? $file : '',
            ]);

            array_push($this->products, $newProduct);
        }
    }
}
