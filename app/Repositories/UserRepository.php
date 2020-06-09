<?php


namespace App\Repositories;

use App\Interfaces\UserInterface;
use App\User;

class UserRepository implements UserInterface{

    public function store($request)
    {

        $user = User::where('email', request('email'))->first();

        //checks if the user exists:
        if (is_null($user)) {
            //if not, it creates a new one
            $user = new User(['email' => request('email'),
                             'last_name' =>request('last_name'),
                             'first_name' =>request('first_name')
            ]);
        }
        $user->save();

        return $user->id;
    }

}
