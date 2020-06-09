<?php

namespace App\Jobs;

use App\Ingredient;
use App\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class storeProducts extends Job
 //StoreProduct Class Uppercase
{

   

    
    /**
     * @var string
     */
    private $title;



    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $title)
    {
        $this->title = $title;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $page = 1;
        $response = Http::get('https://fr.openfoodfacts.org/cgi/search.pl?action=process&search_terms=' . 
        $this->title . '&page=' . $page . '&json=true');

        $data = $response->json();

        while ($page <= $data['page_size']) {


            foreach ($data['products'] as $item) {


                $ingredients = [];
                if (isset($item['ingredients'])) {
                    foreach ($item['ingredients'] as $i) {
                        $ingredient = Ingredient::firstOrCreate([
                            'label' => $i['text']
                        ]);

                        array_push($ingredients, $ingredient);
                    }
                }

                if (isset($item['image_small_url'])) {
                    $url = $item['image_small_url'];
                    $info = pathinfo($url);
                    $contents = file_get_contents($url);
                    $file = storage_path("images/products/product_") . $item['code'] . '.' . $info['extension'];
                    file_put_contents($file, $contents);
                }

                $product = Product::firstOrCreate([
                    'codebar' => $item['code'],
                    'title' => isset($item['product_name']) ? $item['product_name'] : '',
                    'brand' => isset($item['brands']) ? $item['brands'] : '',
                    'nutri_score' => isset($item['nutrition_grades']) ? $item['nutrition_grades'] : '',
                    'nova_group' => isset($item['nova_group']) ? $item['nova_group'] : '',
                    'image' => isset($item['image_small_url']) ? $file : '',
                ]);
                $product->ingredients()->saveMany($ingredients);
            };
            // dd($product);

            ++$page;

            //what this block is doing
            $response = Http::get('https://fr.openfoodfacts.org/cgi/search.pl?action=process&search_terms=' . $this->title . '&page=' . $page . '&json=true');
            $data = $response->json();
        }
    }
}