<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function loadView()
    {
        return view('category');
    }

    public function getAllCategories()
    {
        try {
            $categories = Category::select('gml_category.id', 'gml_category.name')->get();

            $response = ["status" => true, "data" => $categories];
        } catch (\Exception $e) {
            $response = ["status" => false, "msm" => $e->getMessage()];
            dd($e);
        }

        return response()->json($response);
    }
}
