@extends('layouts.export')
@section('title', 'Hasil Pemilu')

@section('table')
    <table>
        <thead>
            <tr>
                <th style="text-align: center">No</th>
                <th>Nama Kandidat</th>
                <th style="text-align: center">Total Pemilih</th>
            </tr>
        </thead>
        <tbody>
            @if ($kandidat->isEmpty())
                <tr>
                    <td colspan="3" style="text-align: center;">Kandidat Tidak Tersedia</td>
                </tr>
            @else
                @foreach ($kandidat as $item)
                    <tr>
                        <td style="text-align: center">{{ $loop->iteration }}</td>
                        <td>{{ $item->name }}</td>
                        <td style="text-align: center">{{ $item->voting()->count() }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="3"></td>
                </tr>
                <tr>
                    <td colspan="2">Jumlah Pemilih</td>
                    <td style="text-align: center">{{ $votedUsers }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    <table style="margin-top: 2rem">
        <thead>
            <tr>
                <th style="text-align: center">No</th>
                <th>Nama Kelas</th>
                <th style="text-align: center">Jumlah Yang Vote</th>
            </tr>
        </thead>
        <tbody>
            @if ($votesPerClass->isEmpty())
            <tr>
                <td colspan="3" style="text-align: center">Data Voting Belum Tersedia</td>
            </tr>
            @else
                @foreach ($votesPerClass as $item)
                    <tr>
                        <td style="text-align: center">{{ $loop->iteration }}</td>
                        <td>{{ $item->name }}</td>
                        <td style="text-align: center">{{ $item->votes_count }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
@endsection
