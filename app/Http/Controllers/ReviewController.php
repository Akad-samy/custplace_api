<?php

namespace App\Http\Controllers;

use App\Interfaces\ReviewInterface;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    //injection of the review Interface
    private $reviewInterface;
    public function __construct(ReviewInterface $reviewInterface) {
        $this->reviewInterface = $reviewInterface;
    }

    
    public function store(Request $request, $product_codebar)
    {
         // Perform Validation
          $this->validate($request, array(
            'last_name' => 'required|max:50',
            'first_name' => 'required|max:50',
            'email' => 'required|email|max:255',
            'comment' => 'required|min:5|max:2000',
            'rate' => 'required|numeric|min:1|max:5'
        ));

        return $this->reviewInterface->store($request, $product_codebar);  
    }


    public function index($product_codebar)
    {
        return $this->reviewInterface->index($product_codebar, request('limit'), request('page'));
    }
}
