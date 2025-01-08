@extends('layouts.app')
@section('title')
Edit Laporan
@endsection

@section('content')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Home /</span> Dashboard</h4>
    <div class="card">
        <h5 class="card-header">Daftar Laporan</h5>

        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                <tr>
                    <th>Bukti kondisi</th>
                    <th>Status</th>
                    <th>Nama Fasum</th>
                    <th>Deskripsi</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                @foreach($laporans as $laporan)
                    <tr>
                        <td><img src="{{asset('laporan/'.$laporan->image_path)}}" class="image"></td>
                        @php
                        $statusArr = ['Antri', 'Dikerjakan', 'Outsource', 'Selesai', 'Tidak terselesaikan'];
                        if($laporan->status == 'Antri'){
                            $status = 'badge bg-warning';
                        }else if($laporan->status == 'Dikerjakan' || 'Outsource'){
                            $status = 'badge bg-info';
                        }else if($laporan->status == 'Selesai'){
                            $status = 'badge bg-success';
                        }else{
                            $status = 'badge bg-danger';
                        }
                        @endphp
                        <td><span class="{{$status}}">{{$laporan->status}}</span></td>
                        <td>{{$laporan->fasum->nama}}</td>
                        <td>{{$laporan->deskripsi}}</td>
                        <td>
                            <button type="button" class="btn btn-icon btn-warning" onclick="">
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
        {{-- <div class="card-footer">
            {{$laporans->links('pagination::bootstrap-5')}}
        </div> --}}
    </div>

    <script src="{{asset('assets/vendor/js/bootstrap.js')}}"></script>
@endsection

