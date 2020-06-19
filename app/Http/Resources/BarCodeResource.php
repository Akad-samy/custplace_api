<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BarCodeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $status =$this['status'];

        if($status == 1){

            $product = $this['product'];

            return [
                'codebar' => $product['code'],
                'title' => isset($product['product_name']) ? $product['product_name'] : null,
                'brand' => isset($product['brands']) ? $product['brands'] : null,
                'nutri_score' => isset($product['nutrition_grades']) ? $product['nutrition_grades'] : null,
                'nova_group' => isset($product['nova_group']) ? $product['nova_group'] : null,
                'image' => isset($product['image_small_url']) ? $product['image_small_url'] : null,
                'ingredients' => isset($product['ingredients']) ? $this->ingredients($product['ingredients']) : null
            ];
        }else {
            return [];
        }

    }

    public function with($request)
    {
       return [
           "status" =>  $this['status'] == 1 ? 1 : 0,
           "message" => $this['status'] == 1 ? 'product is found' : 'product is not found',
       ];
    }

    public function ingredients($ingredients)
    {
        $labels =[];
        foreach($ingredients as $ingredient){
            $labels[] = $ingredient['text'];
        };
        return $labels;
    }
}
