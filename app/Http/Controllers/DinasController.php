<?php

namespace App\Http\Controllers;

use App\Models\Dinas;
use App\Models\User;
use Illuminate\Http\Request;
use Mockery\Exception;

class DinasController extends Controller
{
    public function showCreateUser()
    {
        $dinases = Dinas::all()->sortBy('name');
        return view('dinas.register', compact('dinases'));
    }

    public function createUser(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string',
            'dinas' => 'required|exists:dinas,id',
            'role' => 'required|in:dinas,admin',
        ]);

        $dinas = Dinas::find($request->dinas);
        $kota = str_replace("Dinas ", "", $dinas->nama);

        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->dinas_id = $request->dinas;
            $user->kota = $kota;
            $user->role = $request->role;

            if(!$user->save()){
                throw new Exception('User gagal dibuat');
            }

            $returnObj = ['status' => 'success', 'message' => 'User berhasil dibuat'];
            return redirect()->route('dinas.show-create-user')->with('status', $returnObj);
        } catch (\Exception $e) {
            $returnObj = ['status' => 'error', 'message' => 'User gagal dibuat'];
            return redirect()->route('dinas.show-create-user')->with('status', $returnObj);
        }
    }


}
