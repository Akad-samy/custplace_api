<?php

namespace App\Repositories;

use App\Interfaces\ReviewInterface;
use App\Interfaces\UserInterface;
use App\Review;

use App\Product;


class ReviewRepository implements ReviewInterface
{
    private $userInterface;

    public function __construct(UserInterface $userInterface)
    {
        $this->userInterface = $userInterface;
    }

    
    public function store($request, $product_codebar)
    {

        $product = Product::where('codebar', $product_codebar)->first();
        $review = new Review();

        if(!is_null($product)){
            //Storing Review in Db:
            $review->comment = $request->comment;
            $review->rate = $request->rate;
            $review->product()->associate($product->codebar);
            //Calling UserInterface Store() function the latter returns the User Id:
            $review->user()->associate($this->userInterface->store($request));
            $review->save();
            return response()->json($review, 201);
        }else{
            return "Couldn't find a Record of The Product ID You've Provided";
        }
    
    }


    public function index($product_codebar)
    {

        $review = Review::with('user')->where('product_id', $product_codebar)->paginate(5);
        
        if(count($review) == 0){
            return "Couldn't find a Record of The Product ID You've Provided";
        }else{
            return response()->json($review, 200);

        }
    }
}