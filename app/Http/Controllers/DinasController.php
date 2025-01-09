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
            $user->role = 'dinas';

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

    public function showAdmin(string $id)
    {
        $user = User::find($id);
        $dinases = Dinas::all()->sortBy('name');
        return view('dinas.updateAdmin', compact('user', 'dinases'));
    }

    public function updateUserDinas(Request $request)
    {
        $this->validate($request, [
            'name' => 'nullable|string',
            'password' => 'nullable|string',
            'dinas' => 'required|exists:dinas,id',
        ]);

        try {
            $user = User::find($request->user_id);
            $user->name = $request->name;
            $user->dinas_id = $request->dinas;
            if($request->password){
                $user->password = bcrypt($request->password);
            }
            $user->save();

            $returnObj = ['status' => 'success', 'message' => 'Update berhasil'];
            return redirect()->route('dinas.show-admin')->with('status', $returnObj);
        }catch (Exception $e){
            $returnObj = ['status' => 'error', 'message' => $e];
            return redirect()->route('dinas.show-admin')->with('status', $returnObj);
        }

    }

    public function deleteUserDinas(string $id)
    {
        $user = User::find($id);
        $user->delete();
        $returnObj = ['status' => 'success', 'message' => 'User berhasil dihapus'];
        return redirect()->route('dinas.show-admin')->with('status', $returnObj);
    }
}
