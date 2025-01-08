<?php

namespace App\Http\Controllers;

use App\Models\Fasum;
use App\Models\Laporan;
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
    public function create(Request $request)
    {
        $fasums = $request->session()->get("fasums");
        if (!$fasums) {
            $fasums = array();
        }
        for ($i = 0; $i < count($fasums); $i++) {
            $fasums[$i]["fasum"] = Fasum::find($fasums[$i]["id"]);
        }
        return view("submit", compact("fasums"));
    }


    /**
     * Display  the specified resource.
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

    public function DinasUpdateLaporan(string $id)
    {
        //
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

    public function addToSession(Request $request, $fasumId)
    {
        // Fetch the fasum by ID
        $fasum = Fasum::findOrFail($fasumId);

        // Get the laporan session
        $laporanSession = $request->session()->get('laporans', []);

        // Check if the fasum is already in the session
        $exists = collect($laporanSession)->contains('fasum_id', $fasumId);

        if (!$exists) {
            // Add new fasum to the session
            $laporanSession[] = [
                'fasum_id' => $fasum->id,
                'nama' => $fasum->nama,
                'status' => 'Belum Dilaporkan',
            ];

            // Update session
            $request->session()->put('laporans', $laporanSession);
        }

        return redirect()->route('laporan.create')->with('status', 'Fasum ditambahkan ke laporan!');
    }

    function putReport(Request $request, Fasum $fasum){
        // load report array
        $fasums = $request->session()->get("fasums");
        // create a new array if there are no reports yet
        if (!$fasums) {
          $fasums = array();
        }
        // determine if this is an insert or update operation
        // by finding if the place's id is already in the array
        $idx = -1;
        for ($i = 0; $i < count($fasums); $i++) {
          if ($fasums[$i]["id"] == $fasum->id) {
            $idx = $i;
          }
        }

        if ($idx < 0) {
        // add new report
        $reports[] = ["id" => $fasum->id, "report" => $request->fasum];
        } else {
        // update existing report
        $fasums[$idx]["fasum"] = $request->fasum;
        }
        // save the report array to session
        $request->session()->put("fasums", $reports);
        // redirect to submit page
        return redirect("/submit")->with("status", "Sukses menambah laporan");
    }

    function submit(Request $request){
        // load report array
        $fasums = $request->session()->get("fasums");
        // create a new array if there are no reports yet
        if (!$fasums) {
          $fasums = array();
        }
        // load place data for each report
        for ($i = 0; $i < count($fasums); $i++) {
          $fasums[$i]["fasum"] = Fasum::find($fasums[$i]["id"]);
        }
        // render submit page with all pending reports
        return view("submit", compact("reports"));
      }

}
