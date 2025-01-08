<?php

namespace App\Http\Controllers;

use App\Models\Kandidat;
use App\Models\Kelas;
use App\Models\Notification;
use App\Models\Pemilu;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Str;

class KandidatController extends Controller
{
    public function kandidatPemilu($slug)
    {
        $pemilu = Pemilu::where('slug', $slug)->first();
        $notificationCount = Notification::count();
        $notification = Notification::latest()->limit(5)->get();

        if (!$pemilu) {
            return redirect()->back()->with('error', 'Pemilu yang dicari tidak ditemukan');
        }

        $user = Auth::user();

        if ($user->id != $pemilu->user_id) {
            Alert::warning('Akses Terlarang', 'Anda tidak memiliki akses');
            return redirect()->back();
        }

        $kandidat = Kandidat::where('pemilu_id', $pemilu->id)->get();

        confirmDelete('Hapus Kandidat', 'Apakah kamu yakin ingin menghapus kandidat?');
        return view('manage.pemilu-kandidat', compact([
            'pemilu',
            'kandidat',
            'notification',
            'notificationCount'
        ]), ['menu_type' => 'manage-pemilu']);
    }

    public function addKandidatPemilu($slug, Request $request)
    {
        $pemilu = Pemilu::where('slug', $slug)->first();

        if (!$pemilu) {
            return redirect()->back()->with('error', 'Pemilu yang dicari tidak ditemukan');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'vision_mission' => 'required',
            'image' => 'required|file|image|mimes:png,jpg,jpeg'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
        }

        $fileName = Str::random(8) . '.' . $request->file('image')->getClientOriginalExtension();

        $kandidat = new Kandidat();
        $kandidat->name = $request->name;
        $kandidat->description = $request->description;
        $kandidat->vision_mission = $request->vision_mission;
        $kandidat->image = $request->file('image')->storeAs('pemilu/' . $pemilu->slug, $fileName);
        $kandidat->pemilu_id = $pemilu->id;
        $kandidat->save();

        return redirect()->back()->with('success', 'Kandidat berhasil ditambahkan');
    }

    public function updateKandidatPemilu(Request $request,  $slug, $id)
    {
        $pemilu = Pemilu::where('slug', $slug)->first();

        if (!$pemilu) {
            return redirect()->back()->with('error', 'Pemilu yang dicari tidak ditemukan');
        }

        $kandidat = Kandidat::where('id', $id)->where('pemilu_id', $pemilu->id)->first();

        if (!$kandidat) {
            return redirect()->back()->with('error', 'Kandidat yang dicari tidak ditemukan');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'vision_mission' => 'required',
            'image' => 'nullable|file|image|mimes:png,jpg,jpeg'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
        }

        $kandidat->name = $request->name;
        $kandidat->description = $request->description;
        $kandidat->vision_mission = $request->vision_mission;

        if ($request->hasFile('image')) {
            $fileName = Str::random(8) . '.' . $request->file('image')->getClientOriginalExtension();
            $kandidat->image = $request->file('image')->storeAs('pemilu/' . $pemilu->slug, $fileName);
        }

        $kandidat->save();

        return redirect()->back()->with('success', 'Kandidat berhasil diubah');
    }

    public function deleteKandidatPemilu($slug, $id)
    {
        $pemilu = Pemilu::where('slug', $slug)->first();

        if (!$pemilu) {
            return redirect()->back()->with('error', 'Pemilu yang dicari tidak ditemukan');
        }

        $kandidat = Kandidat::where('id', $id)->where('pemilu_id', $pemilu->id)->first();

        if (!$kandidat) {
            return redirect()->back()->with('error', 'Kandidat yang dicari tidak ditemukan');
        }

        $kandidat->delete();
        return redirect()->back()->with('success', 'Kandidat berhasil dihapus');
    }

    public function exportResultPdf($slug)
    {
        $pemilu = Pemilu::where('slug', $slug)->first();

        if (!$pemilu) {
            return redirect()->back()->with('error', 'Pemilu yang dicari tidak ditemukan');
        }

        $kandidat = Kandidat::where('pemilu_id', $pemilu->id)->get();

        if (!$kandidat) {
            return redirect()->back()->with('error', 'Kandidat yang dicari tidak ditemukan');
        }

        $votedUsers = $pemilu->voting()->distinct('user_id')->count();
        $votesPerClass = Kelas::withCount([
            'user as votes_count' => fn($q) => $q->whereHas('voting', fn($q) => $q->where('pemilu_id', $pemilu->id))
        ])->get();

        $pdf = Pdf::loadView('export.export-result', compact([
            'kandidat',
            'votedUsers',
            'votesPerClass'
        ]));
        return $pdf->download('Hasil ' . $pemilu->name . '.pdf');
    }

    public function data(Request $request, $slug)
    {
        $length = intval($request->input('length', 15));
        $start = intval($request->input('start', 0));
        $search = $request->input('search');
        $columns = $request->input('columns');
        $order = $request->input('order');

        $pemilu = Pemilu::where('slug', $slug)->first();

        $data = Kandidat::query();

        if (!empty($order)) {
            $order = $order[0];
            $orderBy = $order['column'];
            $orderDir = $order['dir'];

            if (isset($columns[$orderBy]['data'])) {
                $data->orderBy($columns[$orderBy]['data'], $orderDir)->where('pemilu_id', $pemilu->id)->with('pemilu');
            } else {
                $data->orderBy('name', 'asc')->where('pemilu_id', $pemilu->id)->with('pemilu');
            }
        } else {
            $data->orderBy('name', 'asc')->where('pemilu_id', $pemilu->id)->with('pemilu');
        }

        $count = $data->count();
        $countFiltered = $count;

        if (!empty($search['value'])) {
            $data->where('fullname', 'LIKE', '%' . $search['value'] . '%');
            $countFiltered = $data->count();
        }

        $data = $data->skip($start)->take($length)->get();

        $response = [
            "draw" => intval($request->input('draw', 1)),
            "recordsTotal" => $count,
            "recordsFiltered" => $countFiltered,
            "limit" => $length,
            "data" => $data
        ];

        return response()->json($response);
    }

    public function dataById($slug, $id)
    {
        $pemilu = Pemilu::where('slug', $slug)->first();

        if (!$pemilu) {
            return response()->json(['message' => 'Pemilu not found'], 404);
        }

        $kandidat = Kandidat::where('id', $id)->where('pemilu_id', $pemilu->id)->first();
        if (!$kandidat) {
            return response()->json(['message' => 'Kandidat not found'], 404);
        }

        return response()->json($kandidat);
    }
}
