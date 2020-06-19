<?php

namespace App\Repositories;

use App\Ingredient;
use App\Interfaces\IngredientInterface;

class IngredientRepository implements IngredientInterface
{
    public function store($product)
    {
        $ingredients = [];
        foreach ($product as $i) {
            $ingredient = Ingredient::firstOrCreate([
                'label' => $i['text']
            ]);

            array_push($ingredients, $ingredient);
        }
        return $ingredients;
    }
}
