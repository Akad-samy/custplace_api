<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{

    protected $table = 'users';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'last_name', 'first_name', 'email'
    ];

    public function reviews() {
        return $this->hasMany(Review::class);
    }

}