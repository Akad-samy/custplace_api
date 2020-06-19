<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollection extends ResourceCollection
{

    private $pagination;

    public function __construct($resource)
    {
        $this->pagination = [
            'count' => $resource->count(),
            'per_page' => $resource->perPage(),
            'current_page' => $resource->currentPage(),
        ];

        $resource = $resource->getCollection();

        parent::__construct($resource);
    }
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        foreach ($this as $product) {
            $products[] = [
                'codebar' => $product->codebar,
                'title' => $product->title,
                'brand' => $product->brand,
                'nutri_score' => $product->nutri_score,
                'nova_group' => $product->nova_group,
                'image' => $product->image,
                'ingredients' => $this->ingredients($product->ingredients)
            ];
        }
        return [
            "data" => $products,
            'pagination' => $this->pagination
        ];
    }

    public function with($request)
    {
        return [
            "status" =>  $this->count() != 0 ? 1 : 0,
            "message" => $this->count() != 0 ? 'product is found' : 'product is not found',
        ];
    }

    public function ingredients($ingredients)
    {
        $labels = [];
        foreach ($ingredients as $ingredient) {
            $labels[] = $ingredient['label'];
        };
        return $labels;
    }
}
