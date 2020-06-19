<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OriginalStore extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = $this['products'];

        if ($this['count'] != 0) {

            foreach ($data as $product) {
                $products[] = [
                    'codebar' => $product['code'],
                    'title' => isset($product['product_name']) ? $product['product_name'] : null,
                    'brand' => isset($product['brands']) ? $product['brands'] : null,
                    'nutri_score' => isset($product['nutrition_grades']) ? $product['nutrition_grades'] : null,
                    'nova_group' => isset($product['nova_group']) ? $product['nova_group'] : null,
                    'image' => isset($product['image_small_url']) ? $product['image_small_url'] : null,
                    'ingredients' => isset($product['ingredients']) ? $this->ingredients($product['ingredients']) : null
                ];
            }
            return $products;
        } else {
            return [];
        }
    }

    public function with($request)
    {
        return [
            "paginate" => [
                "count" => $this['count'],
                "per_page" => $this['page_size'],
                "current_page" => $this['page'],
            ],
            "status" =>  $this['count'] != 0 ? 1 : 0,
            "message" => $this['count'] != 0 ? 'product is found' : 'product is not found',
        ];
    }

    public function ingredients($ingredients)
    {
        $labels = [];
        foreach ($ingredients as $ingredient) {
            $labels[] = $ingredient['text'];
        };
        return $labels;
    }
}
