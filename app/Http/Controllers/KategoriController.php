<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::paginate(5);
        return view('kategori', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
        ]);

        try {
            $kategori = new Kategori();
            $kategori->nama = $request->name;
            $kategori->save();

            $returnObj = ['status' => 'success', 'message' => "Kategori $request->name berhasil ditambahkan"];
            return redirect(route('kategori.index'))->with('status', $returnObj);
        }catch (\Exception $e){
            $returnObj = ['status' => 'error', 'message' => "$e"];
            return redirect(route('kategori.index'))->with('status', $returnObj);
        }
    }
}
