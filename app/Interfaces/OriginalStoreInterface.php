<?php

namespace App\Interfaces;


interface OriginalStoreInterface
{
    public function barcode($code);
    public function title($name, $page_size, $page);
    public function format($data);
}
