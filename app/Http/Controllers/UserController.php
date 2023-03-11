<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function loadView()
    {
        return view('user');
    }

    public function getAllUsers()
    {
        try {
            $users = User::select(
                'gml_user.id',
                'gml_user.id_number',
                DB::raw("CONCAT(gml_user.name,' ', gml_user.last_name) as complete_name"),
                'gml_user.phone_number',
                'gml_user.email',
                'gml_category.name as category_user'
            )
            ->join('gml_category', 'gml_category.id', '=', 'gml_user.category_id')
            ->get();           

            $response = ["status" => true, "data" => $users];
        } catch (\Exception $e) {
            $response = ["status" => false, "msm" => $e->getMessage()];
            dd($e);
        }

        return response()->json($response);
    }

    public function saveUser(Request $request)
    {
        try {
            //code...
        } catch (\Exception $e) {
            //throw $th;
        }
    }
}
