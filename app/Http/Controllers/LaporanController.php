<?php

namespace App\Http\Controllers;

use App\Models\Fasum;
use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $fasums = Fasum::where('dinas_id', Auth::user()->dinas_id)->get();
        return view('laporan.create', compact('fasums'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'deskripsi' => 'required|string',
            'fasums' => 'required|array',
            'fasums.*.fasum_id' => 'required|exists:fasums,id',
            'fasums.*.image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $laporan = new Laporan();
            $laporan->status = 'Antri';
            $laporan->created_by = Auth::id();
            $laporan->deskripsi = $request->deskripsi;
            $laporan->created_at = now();
            $laporan->save();

            foreach ($request->fasums as $fasumData) {
                $imagePath = null;

                if (isset($fasumData['image'])) {
                    $image = $fasumData['image'];
                    $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('laporans'), $imageName);
                    $imagePath = $imageName;
                }

                // Attach facility with additional pivot data
                $laporan->fasum()->attach($fasumData['fasum_id'], [
                    'status' => 'Antri',
                    'image_path' => $imagePath,
                    'created_at' => now(),
                ]);
            }

            DB::commit();

            return redirect()->route('laporan.create')->with('status', [
                'status' => 'success',
                'message' => 'Report submitted successfully!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('laporan.create')->with('status', [
                'status' => 'error',
                'message' => 'Failed to submit the report.',
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $value = $request->session()->get('key');
        $data = $request->session()->all();
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
        $this->validate($request, [
            'status' => 'required|string',
            'image_selesai' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            $laporan = Laporan::findOrFail($id);

            // Only allow government users to update
            if (Auth::user()->role !== 'dinas') {
                return redirect()->back()->with('status', [
                    'status' => 'error',
                    'message' => 'Unauthorized action.',
                ]);
            }

            // Update status and government user ID
            $laporan->status = $request->status;
            $laporan->updated_by = Auth::id();
            $laporan->updated_at = now();
            $laporan->save();

            // Update pivot table for related facilities
            foreach ($laporan->fasum as $fasum) {
                $updateData = [
                    'status' => $request->status,
                    'updated_at' => now(),
                ];

                if ($request->hasFile('image_selesai')) {
                    $image = $request->file('image_selesai');
                    $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('laporans/selesai'), $imageName);
                    $updateData['image_selesai'] = $imageName;
                }

                $laporan->fasum()->updateExistingPivot($fasum->id, $updateData);
            }

            return redirect()->route('laporan.index')->with('status', [
                'status' => 'success',
                'message' => 'Report updated successfully!',
            ]);
        } catch (\Exception $e) {
            return redirect()->route('laporan.index')->with('status', [
                'status' => 'error',
                'message' => 'Failed to update the report.',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
