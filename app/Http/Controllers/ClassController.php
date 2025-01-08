<?php

namespace App\Http\Controllers;

use App\Imports\ClassImport;
use App\Models\Kelas;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ClassController extends Controller
{
    public function manageClass()
    {
        $kelas = Kelas::orderBy('name', 'asc')->get();
        $notification = Notification::latest()->limit(5)->get();
        $notificationCount = Notification::count();

        confirmDelete('Hapus Kelas', 'Apakah kamu yakin ingin menghapus kelas?');
        return view('manage.class', compact([
            'kelas',
            'notification',
            'notificationCount'
        ]), ['menu_type' => 'manage-class']);
    }

    public function addClass(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
        }

        $name = $request->name;
        $toLower = strtolower($name);
        $parts = explode(' ', $name, 2);
        $firstPart = $parts[0];
        $remainingPart = $parts[1] ?? '';

        if (is_numeric($firstPart)) {
            $romanNumeral = $this->toRoman($firstPart);
            $toLower = strtolower($romanNumeral . ' ' . $remainingPart);
        } else {
            $toLower = strtolower($name);
        }

        $slug = str_replace(' ', '-', $toLower);

        $kelas = new Kelas();
        $kelas->name = $request->name;
        $kelas->slug = $slug;
        $kelas->save();

        return redirect()->back()->with('success', 'Kelas berhasil ditambahkan');
    }

    public function updateClass(Request $request, $id)
    {
        $kelas = Kelas::where('id', $id)->first();

        if (!$kelas) {
            return redirect()->back()->with('error', 'Kelas yang ingin diubah tidak ditemukan');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
        }

        $name = $request->name;
        $toLower = strtolower($name);
        $parts = explode(' ', $name, 2);
        $firstPart = $parts[0];
        $remainingPart = $parts[1] ?? '';

        if (is_numeric($firstPart)) {
            $romanNumeral = $this->toRoman($firstPart);
            $toLower = strtolower($romanNumeral . ' ' . $remainingPart);
        } else {
            $toLower = strtolower($name);
        }

        $slug = str_replace(' ', '-', $toLower);

        $kelas->name = $request->name;
        $kelas->slug = $slug;
        $kelas->save();

        return redirect()->back()->with('success', 'Kelas berhasil diubah');
    }

    public function importClass(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:csv,xlsx,xls'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
        }

        Excel::import(new ClassImport, $request->file('file'));

        return redirect()->back()->with('success', 'Data kelas berhasil diimport');
    }

    public function deleteClass($slug)
    {
        $kelas = Kelas::where('slug', $slug)->first();

        if (!$kelas) {
            return redirect()->back()->with('error', 'Kelas yang ingin dihapus tidak ditemukan');
        }

        $kelas->delete();

        return redirect()->back()->with('success', 'Kelas berhasil dihapus');
    }

    public function data(Request $request)
    {
        $length = intval($request->input('length', 15));
        $start = intval($request->input('start', 0));
        $search = $request->input('search');
        $columns = $request->input('columns');
        $order = $request->input('order');

        $data = Kelas::query();

        if (!empty($order)) {
            $order = $order[0];
            $orderBy = $order['column'];
            $orderDir = $order['dir'];

            if (isset($columns[$orderBy]['data'])) {
                $data->orderBy($columns[$orderBy]['data'], $orderDir);
            } else {
                $data->orderBy('name', 'asc');
            }
        } else {
            $data->orderBy('name', 'asc');
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

    public function dataById($id)
    {
        $class = Kelas::where('id', $id)->first();

        return response()->json($class);
    }
}
