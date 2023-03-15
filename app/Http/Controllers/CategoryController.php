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

    public function saveCategory(Request $request)
    {
        try {
            $dataSaved = [
                "name" => $request->category['name'],    
            ];

            if (empty($request->category['id'])) {
                Category::create($dataSaved);
            } else {
                Category::find($request->category['id'])->update($dataSaved);
            }

            $response = ["status" => true, "msm" => "Registro guardado con éxito"];
        } catch (\Exception $e) {
            $response = ["status" => false, "msm" => $e->getMessage()];
            dd($e);
        }

        return response()->json($response);
    }

    public function getCategoryById(Request $request)
    {
        try {
            $category = Category::find($request->idCategory);

            $response = ["status" => true, "data" => $category];
        } catch (\Exception $e) {
            $response = ["status" => false, "msm" => $e->getMessage()];
            dd($e);
        }

        return response()->json($response);
    }

    public function deleteCategory(Request $request)
    {
        try {
            $category = Category::find($request->idCategory);
            $category->delete();

            $response = ["status" => true, "msm" => "Registro borrado con éxito"];
        } catch (\Exception $e) {
            $response = ["status" => false, "msm" => $e->getMessage()];
            dd($e);
        }

        return response()->json($response);
    }
}
