@extends('layouts.app')
@section('title')
Dashboard
@endsection

@section('content')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Home /</span> Dashboard</h4>
    <div class="card">
        <h5 class="card-header">Daftar Laporan</h5>

        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                <tr>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Jumlah Fasum</th>
                    <th>Tanggal Dibuat</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                @foreach($laporans as $laporan)
                    <tr>
                        <td>{{$laporan->subject}}</td>
                        @php
                        $statusArr = ['Antri', 'Dikerjakan', 'Selesai', 'Tidak terselesaikan'];
                        if($laporan->status == 'Antri'){
                            $status = 'badge bg-';
                        }
                        @endphp
                        <td>{{$fasum->nama}}</td>
                        <td>
                            @foreach($fasum->kategori as $kategori)
                                <span class="badge bg-secondary">{{$kategori->nama}}</span>
                            @endforeach
                        </td>
                        <td>{{$fasum->dinas->nama}}</td>
                        <td>
                            <button type="button" class="btn btn-icon btn-warning">
                                <span class="bx bx-edit-alt me-1"></span>
                            </button>
                            <button type="button" class="btn btn-icon btn-danger">
                                <span class="bx bx-trash me-1"></span>
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{$fasums->links('pagination::bootstrap-5')}}
        </div>
    </div>

    <script src="{{asset('assets/vendor/js/bootstrap.js')}}"></script>
@endsection
