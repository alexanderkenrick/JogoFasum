<?php

namespace App\Http\Controllers;

use App\Models\Fasum;
use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $laporans = Laporan::withCount('fasum')
            ->where('dinas_id', Auth::user()->dinas_id)
            ->orderBy('created_at', 'desc')
            ->paginate(5);
        return view('dinas.dashboard', compact('laporans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $laporans = Laporan::with('fasum')
            ->where('id', $id)->firstOrFail();
        // dd($laporans);
        return view("dinas.edit-laporan", compact('laporans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
