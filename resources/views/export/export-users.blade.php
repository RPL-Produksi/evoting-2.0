@extends('layouts.export')
@section('title', 'Export Data User')

@section('table')
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Lengkap</th>
                <th>Username</th>
                <th>Password</th>
                <th>Kelas</th>
            </tr>
        </thead>
        <tbody>
            @if ($users->isEmpty())
                <tr>
                    <td colspan="5" style="text-align: center;">Data User Belum Tersedia</td>
                </tr>
            @else
                @foreach ($users as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->fullname }}</td>
                        <td>{{ $item->username }}</td>
                        <td>{{ $item->unencrypted_password }}</td>
                        <td>{{ $item->kelas->name ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
@endsection
