<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::with('dinas')->where('role', 'dinas')->orderBy('dinas_id')->paginate(1);
        return view('dinas.listAdmin', compact('users'));
    }
}
