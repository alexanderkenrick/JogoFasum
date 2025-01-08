<?php

namespace App\Http\Controllers;

use App\Models\Fasum;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Image;
use Mockery\Exception;

class FasumController extends Controller
{
    public function indexDinas(Request $request)
    {
        $query = Fasum::with('kategori')->with('dinas')
            ->where('dinas_terkait', Auth::user()->dinas_id);

        if ($request->has('filter')) {
            $days = (int) $request->input('filter');
            $dateFrom = Carbon::now()->subDays($days);
            $query->where('created_at', '>=', $dateFrom);
            $query->whereIn('status', ['Antri', 'Dikerjakan']);
        }

        $fasums = $query->paginate(5)->appends($request->except('page'));
        return view('dinas.fasum', compact('fasums'));
    }

    public function createDinas()
    {
        $kategories = Kategori::all();
        return view('dinas.fasumcreate', compact('kategories'));
    }

    public function storeDinas(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'categories' => 'required|array',
            'kondisi' => 'required|string',
            'asal_fasilitas' => 'required|string',
            'lat' => 'required|numeric',
            'long' => 'required|numeric',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'luas' => 'required|numeric'
        ]);

        DB::beginTransaction();
        try {
            $fasum = new Fasum();
            $fasum->nama = $request->name;
            $fasum->kondisi = $request->kondisi;
            $fasum->asal_fasilitas = $request->asal_fasilitas;
            $fasum->dinas_terkait = Auth::user()->dinas_id;
            $fasum->lat = $request->lat;
            $fasum->long = $request->long;
            $fasum->luas = $request->luas;

            $image = $request->file('image');
            $ext = $image->getClientOriginalExtension();
            $imageNewName = uniqid().".$ext";
            $image->move('fasum', $imageNewName);

            $fasum->image_path = $imageNewName;

            $fasum->save();
            $fasum->kategori()->attach($request->categories);
            DB::commit();

            $returnObj = ['status' => 'success', 'message' => "Fasum $request->name berhasil ditambahkan"];
            return redirect(route('dinas.create-fasum'))->with('status', $returnObj);
        }catch (Exception $e){
            DB::rollBack();
            $returnObj = ['status' => 'error', 'message' => "$e"];
            return redirect(route('dinas.create-fasum'))->with('status', $returnObj);
        }
    }
}
