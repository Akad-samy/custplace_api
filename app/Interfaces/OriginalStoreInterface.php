<?php

namespace App\Interfaces;


interface OriginalStoreInterface
{
    public function getByBarcode($code);
    public function getByTitle($name, $page_size, $page);
    // public function format($data);
}
