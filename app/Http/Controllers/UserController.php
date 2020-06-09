<?php

namespace App\Http\Controllers;

use App\Interfaces\UserInterface;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $userInterface;

    public function __construct(UserInterface $userInterface){
        $this->userInterface = $userInterface;
    }
 

    public function store(Request $request)
    {
        return $this->userInterface->store($request);
    }

}
