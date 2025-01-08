<?php

namespace App\Http\Controllers;

use App\Models\Kandidat;
use App\Models\Notification;
use App\Models\Pemilu;
use App\Models\VoteLogs;
use App\Models\Voting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class DashboarController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        if ($user->role == 'admin') {
            $pemilu = Pemilu::orderBy('created_at', 'DESC')->where('status', 1)->where('user_id', $user->id)->get();
        } else {
            $pemilu = Pemilu::orderBy('created_at', 'DESC')->where('status', 1)->get();
        }


        $notificationCount = Notification::count();
        $notification = Notification::latest()->limit(5)->get();

        return view('dashboard', compact([
            'pemilu',
            'notification',
            'notificationCount',
        ]), ['menu_type' => 'dashboard']);
    }

    public function joinPemilu($slug)
    {
        $pemilu = Pemilu::where('slug', $slug)->first();

        if (!$pemilu) {
            return redirect()->back()->with('error', 'Pemilu yang dicari tidak ditemukan');
        }

        $user = Auth::user();
        $userVoted = Voting::where('pemilu_id', $pemilu->id)->where('user_id', $user->id)->first();
        if ($userVoted) {
            Alert::warning('Peringatan', 'Anda Sudah Melakukan Voting');
            return redirect()->back();
        }

        $kandidat = Kandidat::where('pemilu_id', $pemilu->id)->get();
        return view('join-pemilu', compact([
            'pemilu',
            'kandidat'
        ]), ['menu_type' => 'dashboard']);
    }

    public function verifyPasswordJoin(Request $request, $slug)
    {
        $pemilu = Pemilu::where('slug', $slug)->first();

        if (!$pemilu) {
            return redirect()->back()->with('error', 'Pemilu yang dicari tidak ditemukan');
        }

        $validator = Validator::make($request->all(), [
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
        }

        if ($request->password == $pemilu->password) {
            Session::put('pemilu_' . $pemilu->slug . '_verified', true);
        } else {
            Alert::error('Error', 'Password Salah');
            return redirect()->back();
        }

        // dd(Session::get('pemilu_' . $pemilu->slug . '_verified'));
        return redirect()->route('user.pemilu.join', $slug);
    }

    public function votePemilu($slug, $id)
    {
        $pemilu = Pemilu::where('slug', $slug)->first();
        if (!$pemilu) {
            return redirect()->back()->with('error', 'Pemilu yang dicari tidak ditemukan');
        }

        $kandidat = Kandidat::where('id', $id)->where('pemilu_id', $pemilu->id)->first();
        if (!$kandidat) {
            return redirect()->back()->with('error', 'Kandidat yang dicari tidak ditemukan');
        }

        $voting = new Voting();
        $voting->pemilu_id = $pemilu->id;
        $voting->kandidat_id = $kandidat->id;
        $voting->user_id = Auth::id();
        $voting->save();

        if ($voting->save()) {
            Session::remove('pemilu_' . $pemilu->slug . '_verified');

            $user = Auth::user();
            $voteLogs = new VoteLogs();
            $voteLogs->user_id = $user->id;
            $voteLogs->pemilu_id = $pemilu->id;
            $voteLogs->vote_time = Carbon::now()->translatedFormat('l, d F Y | H.i');
            $voteLogs->save();

            $notification = new Notification();
            $notification->user_id = Auth::id();
            $notification->pemilu_id = $pemilu->id;
            $notification->save();

            Alert::success('Success', 'Anda telah melakukan voting');
            return redirect()->route('user.dashboard');
        }
    }
}
