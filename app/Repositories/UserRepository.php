<?php


namespace App\Repositories;

use App\Interfaces\UserInterface;
use App\User;

class UserRepository implements UserInterface
{

    public function store($request)
    {

        $user = User::where('email', $request->email)->first();
        try {
            if (is_null($user)) {
                $user = new User([
                    'email' => $request->email,
                    'last_name' => $request->last_name,
                    'first_name' => $request->first_name
                ]);
            }
            $user->save();
        } catch (\Throwable $th) {
            return $th;
        }
        return $user;
    }
}
