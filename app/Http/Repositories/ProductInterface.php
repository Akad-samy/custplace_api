<?php

namespace App\Http\Repositories;


interface ProductInterface
{
    public function all();
    public function title(string $title);
    public function barcode(string $barcode);
}
