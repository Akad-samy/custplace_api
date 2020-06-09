<?php

namespace App\Repositorie;

interface ProductInterface
{
    public function all($product);

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function title(string $title);

    /**
     * Display the specified resource.
     *
     * @param \App\Product $product
     * @return \Illuminate\Http\Response
     */
    public function barcode(string $barcode);
}