<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|string",
            "email" => "required|email",
            "password" => "required|string"
        ]);

        $users = [
            "name" => $request->name,
            "email"=> $request->email,
            "password" => Hash::make($request->password),
            "is_admin" => true
        ];

        $user = User::create($users);
        return redirect()->route('dashboard')->with("success","berhasil menambahkan data");
    }

    public function delete(Request $request,$id){
        $user = User::findOrFail($id);
        if($user->is_admin == true){
            $user->delete();
            return redirect()->route('dashboard')->with("success","berhasil menghapus data");
        }
        return redirect()->route('dashboard')->with('error', 'user tidak dapat dihapus');
    }
}
