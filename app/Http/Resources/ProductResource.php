<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'codebar' => $this->codebar,
            'title' => $this->title,
            'brand' => $this->brand,
            'nutri_score' => $this->nutri_score,
            'nova_group' => $this->nova_group,
            'image' => $this->image,
            'ingredients' => $this->ingredients($this->ingredients)
        ];
    }
    public function with($request)
    {
        return [
            "status" =>  $this->codebar ? 1 : 0,
            "message" => $this->codebar ? 'product is found' : 'product is not found',
        ];
    }

    public function ingredients($ingredients)
    {
        $label = [];
        foreach ($ingredients as $ingredient) {
            $labels[] = $ingredient['label'];
        };
        return $labels;
    }


}
