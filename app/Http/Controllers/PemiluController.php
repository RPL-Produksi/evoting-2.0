<?php

namespace App\Http\Controllers;

use App\Models\Kandidat;
use App\Models\Kelas;
use App\Models\Notification;
use App\Models\Pemilu;
use App\Models\User;
use App\Models\VoteLogs;
use App\Models\Voting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;

class PemiluController extends Controller
{
    public function managePemilu()
    {
        $user = Auth::user();
        $pemilu = Pemilu::orderBy('created_at', 'DESC')->where('user_id', $user->id)->get();
        $notificationCount = Notification::count();
        $notification = Notification::latest()->limit(5)->get();

        // $kandidat = Kandidat::where('pemilu_id', )

        confirmDelete('Hapus Pemilu', 'Apakah kamu yakin ingin menghapus pemilu?');
        return view('manage.pemilu', compact([
            'pemilu',
            'notification',
            'notificationCount'
        ]), ['menu_type' => 'manage-pemilu']);
    }

    public function addPemilu(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'is_private' => 'required',
            'password' => 'required_if:is_private,1',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
        }

        $toLower = strtolower($request->name);
        $slug = str_replace([' ', '/'], '-', $toLower);

        if ($request->is_private == 0) {
            $request->merge(['password' => null]);
        }

        $pemilu = new Pemilu();
        $pemilu->name = $request->name;
        $pemilu->slug = $slug;
        $pemilu->description = $request->description;
        $pemilu->is_private = (int)$request->is_private;
        $pemilu->password = $request->password;
        $pemilu->status = (int)$request->status;
        $pemilu->user_id = Auth::user()->id;
        $pemilu->save();

        return redirect()->back()->with('success', 'Pemilu berhasil ditambahkan');
    }

    public function updatePemilu(Request $request, $slug)
    {
        $pemilu = Pemilu::where('slug', $slug)->first();

        if (!$pemilu) {
            return redirect()->back()->with('error', 'Pemilu yang dicari tidak ditemukan');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'is_private' => 'required',
            'password' => 'required_if:is_private,1',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
        }

        $toLower = strtolower($request->name);
        $slug = str_replace([' ', '/'], '-', $toLower);

        if ($request->is_private == 0) {
            $request->merge(['password' => null]);
        }

        $pemilu->name = $request->name;
        $pemilu->slug = $slug;
        $pemilu->description = $request->description;
        $pemilu->is_private = (int)$request->is_private;
        $pemilu->password = $request->password;
        $pemilu->status = (int)$request->status;
        $pemilu->user_id = Auth::user()->id;
        $pemilu->save();

        return redirect()->back()->with('success', 'Pemilu berhasil diubah');
    }

    public function deletePemilu($slug)
    {
        $pemilu = Pemilu::where('slug', $slug)->first();

        if (!$pemilu) {
            return redirect()->back()->with('error', 'Pemilu yang ingin dihapus tidak ditemukan');
        }

        $user = Auth::user();
        if ($user->id != $pemilu->user_id) {
            Alert::warning('Akses Terlarang', 'Anda tidak memiliki akses');
            return redirect()->back();
        }

        $pemilu->delete();
        return redirect()->back()->with('success', 'Pemilu berhasil dihapus');
    }

    public function data(Request $request)
    {
        $length = intval($request->input('length', 15));
        $start = intval($request->input('start', 0));
        $search = $request->input('search');
        $columns = $request->input('columns');
        $order = $request->input('order');

        $user = Auth::user();
        $data = Pemilu::query();

        if (!empty($order)) {
            $order = $order[0];
            $orderBy = $order['column'];
            $orderDir = $order['dir'];

            if (isset($columns[$orderBy]['data'])) {
                $data->orderBy($columns[$orderBy]['data'], $orderDir);
            } else {
                $data->orderBy('name', 'asc')->where('user_id', $user->id);
            }
        } else {
            $data->orderBy('name', 'asc')->where('user_id', $user->id);
        }

        $count = $data->count();
        $countFiltered = $count;

        if (!empty($search['value'])) {
            $data->where('name', 'LIKE', '%' . $search['value'] . '%');
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

    public function dataBySlug($slug)
    {
        $pemilu = Pemilu::where('slug', $slug)->first();

        if (!$pemilu) {
            return response()->json(['message' => 'Pemilu not found'], 404);
        }

        return response()->json($pemilu);
    }

    public function dataResultVoting($slug)
    {
        $pemilu = Pemilu::where('slug', $slug)->first();

        if (!$pemilu) {
            return response()->json(['message' => 'Pemilu not found'], 404);
        }

        $kandidat = Kandidat::where('pemilu_id', $pemilu->id)->withCount('voting')->get();

        if ($kandidat->isEmpty()) {
            return response()->json(['message' => 'Kandidat not found'], 404);
        }

        $labels = [];
        $data = [];

        foreach ($kandidat as $item) {
            $labels[] = $item->name;
            $data[] = $item->voting_count;
        }

        $totalUsers = User::where('role', '!=', 'admin')->count();
        $votedUsers = $pemilu->voting()->distinct('user_id')->count();
        $notVotedUsers = $totalUsers - $votedUsers;

        $votesPerClass = Kelas::withCount([
            'user as votes_count' => fn($q) => $q->whereHas('voting', fn($q) => $q->where('pemilu_id', $pemilu->id))
        ])->get()->map(fn($kelas) => [
            'name' => $kelas->name,
            'votes_count' => $kelas->votes_count
        ]);


        return response()->json([
            'total_users' => $totalUsers,
            'pie_charts' => [
                'voted' => $votedUsers,
                'not_voted' => $notVotedUsers,
            ],
            'bar_charts' => [
                'labels' => $labels,
                'data' => $data
            ],
            'votes_per_class' => $votesPerClass,
        ]);
    }

    public function dataVoteLogs($slug)
    {
        $pemilu = Pemilu::where('slug', $slug)->first();

        if (!$pemilu) {
            return response()->json(['message' => 'Pemilu not found'], 404);
        }

        $logs = VoteLogs::where('pemilu_id', $pemilu->id)->with(['user:id,fullname', 'pemilu:id,name'])->get();
        return response()->json([
            'name' => $pemilu->name,
            'voteLogs' => $logs
        ]);
    }
}
