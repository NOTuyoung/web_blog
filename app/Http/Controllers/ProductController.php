<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function list()
    {
        return "Список товаров";
    }

    public function details($id)
    {
        return "Детали товара № " . $id;
    }
}
