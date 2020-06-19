<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ReviewCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public function __construct($resource)
    {
        
        $this->pagination = [
            'total' => $resource->total(),
            'count' => $resource->count(),
            'per_page' => $resource->perPage(),
            'current_page' => $resource->currentPage(),
            'total_pages' => $resource->lastPage()
        ];
    
        $resource = $resource->getCollection();
        
         parent::__construct($resource);
    }
public function toArray($request)
{
    return [
        'data' => $this->collection,
        'pagination' => $this->pagination
    ];
}

    public function with($request)
    {

        return [
            "status" => count(parent::toArray($request)) != 0 ? 1 : 0,
            "message" => count(parent::toArray($request)) != 0 ? "Records Fetched Successfully" : "Records Fetching Failed",
        ];
    }
}
