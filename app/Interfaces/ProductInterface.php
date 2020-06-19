<?php

namespace App\Interfaces;

interface ProductInterface
{
    public function all($product, $page_size=null, $page=null);
    public function getByTitle(string $title, $page_size, $page);
    public function getByBarcode(string $barcode);
    public function store(array $product);
    // protected function format($data);
}
