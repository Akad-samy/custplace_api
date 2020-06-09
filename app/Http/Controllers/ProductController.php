<?php

namespace App\Http\Controllers;

use App\Interfaces\ProductInterface as ProductInterface;

class ProductController extends Controller
{
    private $productInterface;

    public function __construct(ProductInterface $productInterface) {
        $this->productInterface = $productInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->productInterface->all(request('search'), request('page_size'), request('page'));
    }

}
