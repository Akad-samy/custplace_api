<?php

namespace App\Repositories;

use App\Http\Resources\OriginalStore as OriginalStoreCollection;
use App\Http\Resources\BarCodeResource;
use App\Interfaces\OriginalStoreInterface;
use Illuminate\Support\Facades\Http;

class OriginalStoreRepository implements OriginalStoreInterface
{

    private $URL;

    public function __construct()
    {
        $this->URL = "https://fr.opnfoodfacts.org/";
    }

    public function getByBarcode($code)
    {
        try {
            $response = Http::get($this->URL . 'api/v0/product/' . $code);
            return new BarCodeResource($response->json());
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function getByTitle($name, $page_size, $page)
    {

        try {
            $response = Http::get($this->URL . 'cgi/search.pl?action=process&search_terms=' . $name . '&page=' . $page . '&page_size=' . $page_size  .  '&json=true');
            return new OriginalStoreCollection($response->json());
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

    }
}
