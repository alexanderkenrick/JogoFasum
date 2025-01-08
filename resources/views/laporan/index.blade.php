@extends('layouts.app')
@section('title')
Laporan
@endsection

@section('content')
<div class="container">
    <h2>Laporan Fasum</h2>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Status</th>
                <th>Description</th>
                <th>Created At</th>
                <th>Fasums</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($laporans as $laporan)
                <tr>
                    <td>{{ $laporans->id }}</td>
                    <td>{{ $laporans->status }}</td>
                    <td>{{ $laporan->deskripsi }}</td>
                    <td>{{ $laporan->created_at }}</td>
                    <td>
                        @foreach ($laporan->laporanFasums as $fasum)
                            {{ $fasum->fasum->nama }},
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $laporans->links() }}
</div>
@endsection
