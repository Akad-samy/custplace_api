<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'reviews';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'product_id', 'comment', 'rate'
    ];



    protected $hidden = [
        'updated_at', 'user_id', 'created_at'
    ];

    protected $appends =['published_at'];
    
    public function getPublishedAtAttribute(){
                return $this->created_at->diffForHumans();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
