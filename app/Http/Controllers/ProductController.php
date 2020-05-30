<?php

namespace App\Http\Controllers;

use App\Http\Repositories\ProductInterface as ProductInterface;

class ProductController extends Controller
{
    /**
     * @var App\Http\Repositories\ProductInterface
     */
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
        return $this->productInterface->all();
    }

}
