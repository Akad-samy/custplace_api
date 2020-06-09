<?php

namespace App\Repositories;

use App\Ingredient;
use App\Interfaces\IngredientInterface;

class IngredientRepository implements IngredientInterface
{
    public function store($product)
    {
        $ingredients = [];
        if (isset($product)) {
            foreach ($product as $i) {
                $ingredient = Ingredient::firstOrCreate([
                    'label' => $i
                ]);

                array_push($ingredients, $ingredient);
            }
        }
        return $ingredients;
    }
}
