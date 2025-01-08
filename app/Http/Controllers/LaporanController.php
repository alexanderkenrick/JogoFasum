<?php

namespace App\Http\Controllers;

use App\Models\Fasum;
use App\Models\Laporan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sessionReports = Session::get('laporan_cart', []);
        $laporan = Laporan::where('created_by', Auth::id())->latest()->first();

        return view('laporan.index', compact('laporan', 'sessionReports'));
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
    // public function store(Request $request)
    // {
    //     $this->validate($request, [
    //         'deskripsi' => 'required|string',
    //         'fasums' => 'required|array',
    //         'fasums.*.fasum_id' => 'required|exists:fasums,id',
    //         'fasums.*.image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    //     ]);

    //     DB::beginTransaction();
    //     try {
    //         $laporan = new Laporan();
    //         $laporan->status = 'Antri';
    //         $laporan->created_by = Auth::id();
    //         $laporan->deskripsi = $request->deskripsi;
    //         $laporan->created_at = now();
    //         $laporan->save();

    //         foreach ($request->fasums as $fasumData) {
    //             $imagePath = null;

    //             if (isset($fasumData['image'])) {
    //                 $image = $fasumData['image'];
    //                 $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
    //                 $image->move(public_path('laporans'), $imageName);
    //                 $imagePath = $imageName;
    //             }

    //             // Attach facility with additional pivot data
    //             $laporan->fasum()->attach($fasumData['fasum_id'], [
    //                 'status' => 'Antri',
    //                 'image_path' => $imagePath,
    //                 'created_at' => now(),
    //             ]);
    //         }

    //         DB::commit();

    //         return redirect()->route('laporan.create')->with('status', [
    //             'status' => 'success',
    //             'message' => 'Report submitted successfully!',
    //         ]);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return redirect()->route('laporan.create')->with('status', [
    //             'status' => 'error',
    //             'message' => 'Failed to submit the report.',
    //         ]);
    //     }
    // }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request)
    {
        $value = $request->session()->get('key');
        $data = $request->session()->all();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function DinasEditLaporan(string $id)
    {
        $laporans = Laporan::with('fasum')
            ->where('id', $id)->first();
        // dd($laporans);
        return view("dinas.edit-laporan", compact('laporans'));
    }

    public function DinasUpdateLaporan(Request $request)
    {
        $request->validate([
            'image_selesai' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'keterangan_dinas' => 'nullable|string',
        ]);
        // dd($request);

        try{
            if ($request->hasFile('image_selesai')) {
                $image = $request->file('image_selesai');
                $ext = $image->getClientOriginalExtension();
                $imageNewName = uniqid().".$ext";
                $image->move('laporan', $imageNewName);

                $laporan = Laporan::find($request->laporan_id);
                $laporan->fasum()->updateExistingPivot($request->fasum_id, ['image_selesai' => $imageNewName, 'status' => 'Selesai']);
            }
            else { //this is 'tidak terselesaikan' case
                $laporan = Laporan::find($request->laporan_id);
                $laporan->fasum()->updateExistingPivot($request->fasum_id, ['keterangan_dinas' => $request->keterangan_dinas]);
            }
            $returnObj = ['status' => 'success', 'message' => "Laporan berhasil diubah"];
            return redirect(route('dinas.edit-laporan', $laporan->id))->with('status', $returnObj);
        }catch(Exception $e){
            $returnObj = ['status' => 'error', 'message' => $e];
            return redirect(route('dinas.edit-laporan', $laporan->id))->with('status', $returnObj);
        }


    }

    public function DinasUpdateFasum(Request $request)
    {
        $laporan = Laporan::find($request->laporan_id);
        $laporan->fasum()->updateExistingPivot($request->fasum_id, ['status' => $request->status]);
        return response()->json(["message" => "Update status berhasil"], 200);
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

    public function fasumList()
    {
        $fasums = Fasum::where('dinas_terkait', Auth::user()->dinas_id)->get();

        return view('laporan.fasum-list', compact('fasums'));
    }
}
