<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return "Список всех пользователей";
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return "Профиль пользователя ID: " . $id;
    }

}
