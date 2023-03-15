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
            // dd($request->user['name']);
            $dataSaved = [
                "name"         => $request->user['name'],
                "last_name"    => $request->user['last_name'],
                "id_number"    => trim($request->user['id_number']),
                "phone_number" => $request->user['phone_number'],
                "email"        => $request->user['email'],
                "address"      => $request->user['address'],
                "country"      => $request->user['country'],
                "category_id"  => $request->user['category_id'],
            ];

            if (empty($request->user['id'])) {
                User::create($dataSaved);


            } else {
                User::find($request->user['id'])->update($dataSaved);
            }

            $response = ["status" => true, "msm" => "Registro guardado con éxito"];
        } catch (\Exception $e) {
            $response = ["status" => false, "msm" => $e->getMessage()];
            dd($e);
        }

        return response()->json($response);
    }

    public function validateUser(Request $request)
    {
        try {
            if ($request->field == "idNumber") {      
                $user = User::where('id_number','=', trim($request->dataSend))->count();
            } else if ($request->field == "email") {
                $user = User::where('email', '=', trim($request->dataSend))->count();
            }
            // dd($user);

            $response = ["status" => true, "count" => $user];
        } catch (\Exception $e) {
            $response = ["status" => false, "msm" => $e->getMessage()];
            dd($e);
        }

        return response()->json($response);
    }

    public function getUserById(Request $request)
    {
        try {
            $user = User::find($request->idUser);

            $response = ["status" => true, "data" => $user];
        } catch (\Exception $e) {
            $response = ["status" => false, "msm" => $e->getMessage()];
            dd($e);
        }

        return response()->json($response);
    }

    public function getUsersByCountry()
    {
        try {
            $usersByCountries = User::select(
                'country',
                DB::raw('count(1) as cantidad')
            )
            ->groupBy('country')
            ->get();

            $response = ["status" => true, "data" => $usersByCountries];
        } catch (\Exception $e) {
            $response = ["status" => false, "msm" => $e->getMessage()];
            dd($e);
        }

        return $response;
    }

    public function deleteUser(Request $request)
    {
        try {
            $user=User::find($request->idUser);
            $user->delete();

            $response = ["status" => true, "msm" => "Registro borrado con éxito"];
        } catch (\Exception $e) {
            $response = ["status" => false, "msm" => $e->getMessage()];
            dd($e);
        }

        return response()->json($response);
    }

    public function sendUserNotification($dataUser)
    {
        # code...
    }

    public function sendAdminStatistics()
    {
        # code...
    }
}
