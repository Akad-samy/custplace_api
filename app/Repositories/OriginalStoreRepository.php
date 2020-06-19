<?php

namespace App\Repositories;

use App\Interfaces\OriginalStoreInterface;
use Illuminate\Support\Facades\Http;

class OriginalStoreRepository implements OriginalStoreInterface
{

    private $URL;

    public function __construct()
    {
        $this->URL = "https://fr.openfoodfacts.org/";
    }

    public function barcode($code)
    {
        $response = Http::get($this->URL . 'api/v0/product/' . $code);

        if($response['status'] == 1){
            $product = $response->json()['product'];
            return $this->format($product);
        }else {
            return $response['status'];
        }


    }

    public function title($name, $page_size, $page)
    {

        $response = Http::get($this->URL . 'cgi/search.pl?action=process&search_terms=' . $name . '&page=' . $page . '&page_size=' . $page_size  .  '&json=true');
        $results = $response->json()['products'];

        $products = [];

        foreach ($results as $result) {
            $products[] = $this->format($result);
        }

        return [
            'products' => $products,
            'count' => $response->json()['count'],
            'page' => $response->json()['page'],
            'page_size' => $response->json()['page_size']
        ];
    }

    public function format($data) {

        $ingredients = [];
        if (isset($data['ingredients'])) {
            foreach ($data['ingredients'] as $i) {
                $ingredients[] = $i['text'];
            }
        }

        $product =  [
            'codebar' => $data['code'],
            'title' => isset($data['product_name']) ? $data['product_name'] : null,
            'brand' => isset($data['brands']) ? $data['brands'] : null,
            'nutri_score' => isset($data['nutrition_grades']) ? $data['nutrition_grades'] : null,
            'nova_group' => isset($data['nova_group']) ? $data['nova_group'] : null,
            'image' => isset($data['image_small_url']) ? $data['image_small_url'] : null,
            'ingredients' => isset($data['ingredients']) ? $ingredients : null,
        ];

        return $items[] = $product;
    }
}
