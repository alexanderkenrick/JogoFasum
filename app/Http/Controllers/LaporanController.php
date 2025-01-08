<?php

namespace App\Http\Controllers;

use App\Models\Fasum;
use App\Models\Laporan;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users = User::withCount('laporan')
            ->having('laporan_count', '>', 0)
            ->orderBy('laporan_count', 'desc')
            ->limit(5)
            ->get();

        $query = Laporan::withCount('fasum')
            ->with('update_by')
            ->where('dinas_id', Auth::user()->dinas_id);

        if ($request->has('filter')) {
            $days = (int) $request->input('filter');
            $dateFrom = Carbon::now()->subDays($days);
            $query->where('created_at', '>=', $dateFrom);
            $query->whereIn('status', ['Antri', 'Dikerjakan']);
        }

        $laporans = $query->orderBy('created_at', 'desc')
            ->paginate(5);
        return view('dinas.dashboard', compact('laporans', 'users'));
    }

    public function indexWarga()
    {
        $jumlahLaporan = Laporan::where('created_by', Auth::id())
            ->where('dinas_id', Auth::user()->dinas_id)
            ->count();

        $laporans = Laporan::withCount('fasum')
            ->with('update_by')
            ->where('created_by', Auth::id())
            ->where('dinas_id', Auth::user()->dinas_id)
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('warga.dashboard', compact('laporans', 'jumlahLaporan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $fasums = $request->session()->get("fasums", []);
        $fasumArr = [];
        foreach ($fasums as $fasum) {
            $fasumArr[] = Fasum::find($fasum);
        }
        return view("laporan.create", compact("fasumArr"));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'subject' => 'required|string',
            'fasums' => 'required|array',
        ]);
        DB::beginTransaction();
        try {
            $laporan = new Laporan();
            $laporan->dinas_id = Auth::user()->dinas_id;
            $laporan->created_by = Auth::id();
            $laporan->subject = $request->subject;
            $laporan->save();

            $fasums = $request->fasums;
            foreach ($fasums as $fasum) {
                $laporan->fasum()->attach($fasum['id'], ['deskripsi' => $fasum['deskripsi']]);
            }

            DB::commit();
            $request->session()->forget('fasums');

            session()->flash('status', 'success');
            session()->flash('message', 'Sukses menambahkan Laporan');
            return redirect()->route('laporan.create');
        }catch (HttpException $e){
            DB::rollBack();
            session()->flash('status', 'success');
            session()->flash('message', $e);
            return redirect()->route('laporan.create');
        }
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

    public function addToSession(Request $request, $fasumId)
    {
        $carts = $request->session()->get('fasums', []);

        try {
            $carts[$fasumId] = $fasumId;
            $request->session()->put('fasums', $carts);

            session()->flash('status', 'success');
            session()->flash('message', 'Sukses menambahkan Fasum');

            return redirect()->route('laporan.fasumList');
        }catch (Exception $e){
            session()->flash('status', 'error');
            session()->flash('message', 'Gagal menambahkan Fasum');
            return redirect()->route('laporan.fasumList');
        }
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
