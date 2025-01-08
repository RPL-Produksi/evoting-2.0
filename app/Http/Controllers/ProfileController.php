<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        $notificationCount = Notification::count();
        $notification = Notification::latest()->limit(5)->get();

        return view('profile', compact([
            'user',
            'notificationCount',
            'notification',
        ]), ['menu_type' => '']);
    }

    public function updateProfile(Request $request)
    {
        $user = User::where('id', Auth::id())->first();
        $validator = Validator::make($request->all(), [
            'fullname' => 'required',
            'username' => 'required|min:3',
            'password' => 'required|min:4'
        ]);


        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
        }

        $user->fullname = $request->fullname;
        $user->username = $request->username;
        if ($request->password == $user->unencrypted_password) {
            $user->save();
        } else {
            $user->password =  bcrypt($request->password);
            $user->unencrypted_password = $request->password;
            $user->save();
        }

        return redirect()->back()->with('success', 'Profil berhasil diperbarui');
    }

    public function updateProfileImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'file|image|mimes:png,jpg,jpeg,gif'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
        }

        $fileName = Str::random(8) . '.' . $request->file('image')->getClientOriginalExtension();

        $user = User::where('id', Auth::id())->first();
        $user->profile_picture = $request->file('image')->storeAs('profile/'. $user->username, $fileName);
        $user->save();

        return redirect()->back()->with('success', 'Foto profil berhasil diubah');
    }
}
