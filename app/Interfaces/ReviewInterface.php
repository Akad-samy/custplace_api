<?php

namespace App\Interfaces;


interface ReviewInterface
{
    public function store($request, $product_codebar);
    public function index($product_codebar, $limit, $page);
}
