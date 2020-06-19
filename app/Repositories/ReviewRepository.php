<?php

namespace App\Repositories;

use App\Http\Resources\ReviewCollection;
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
        $user = $this->userInterface->store($request);

        $review = new Review();
        try {
            //Storing Review in Db:
            $review->comment = $request->comment;
            $review->rate = $request->rate;
            $review->product()->associate($product->codebar);
            //Calling UserInterface Store() function the latter returns the User Id:
            $review->user()->associate($user->id);
            $review->save();
        } catch (\Exception $e) {
             
            return $e->getCode();
        }


        return response()->json($review, 201);
    }


    public function index($product_codebar, $limit, $page)
    {
        is_null($limit) ? $limit = 5 : $limit;
        is_null($page) ? $page = 1 : $page;

        $reviews = Review::with('user')->where('product_id', $product_codebar)->paginate($limit, ['*'], 'page', $page);

        
         return new ReviewCollection($reviews);
    }
}

