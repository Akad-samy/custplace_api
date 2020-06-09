<?php

namespace App\Interfaces;

interface ProductInterface
{
    public function all($product, $page_size=null, $page=null);
    public function title(string $title, $page_size, $page);
    public function barcode(string $barcode);
    public function store(array $product);
    public function format($data);
}
