<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'codebar', 'title', 'brands', 'nutriScore', 'novaScore', 'image'
    ];

    public function reviews() {
        return $this->hasMany(Review::class);
    }

    public function ingredients() {
        return $this->belongsToMany(Ingredient::class);
    }
}
