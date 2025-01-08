@extends('layouts.app')
@section('title')
    Dashboard
@endsection

@section('content')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Home /</span> Dashboard</h4>

    <div class="card mb-3">
        <h5 class="card-header">Jumlah laporan bulan ini: </h5>
    </div>

    <div class="card">
        <h5 class="card-header">Daftar Laporan</h5>
        <div class="row mx-3">
            <div class="col-2">Filter Belum Selesai:</div>
            <div class="col">
                <form method="GET" action="{{ route('dinas.dashboard') }}">
                    <div class="input-group">
                        <select name="filter" class="form-select" onchange="this.form.submit()">
                            <option value="">Pilih jumlah hari</option>
                            <option value="7" {{ request('filter') == 7 ? 'selected' : '' }}>7 Hari</option>
                            <option value="14" {{ request('filter') == 14 ? 'selected' : '' }}>14 Hari</option>
                            <option value="30" {{ request('filter') == 30 ? 'selected' : '' }}>30 Hari</option>
                        </select>
                        <button class="btn btn-primary" type="submit">Filter</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                <tr>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Jumlah Fasum</th>
                    <th>Tanggal Dibuat</th>
                    <th>Diupdate oleh</th>
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
                                $status = 'badge bg-warning';
                            }else if($laporan->status == 'Dikerjakan'){
                                $status = 'badge bg-info';
                            }else if($laporan->status == 'Selesai'){
                                $status = 'badge bg-success';
                            }else{
                                $status = 'badge bg-danger';
                            }
                        @endphp
                        <td><span class="{{$status}}">{{$laporan->status}}</span></td>
                        <td>{{$laporan->fasum_count}}</td>
                        <td>{{$laporan->created_at}}</td>
                        <td>{{$laporan->updated_by->name}}</td>
                        <td>
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
            {{$laporans->links('pagination::bootstrap-5')}}
        </div>
    </div>

    <script src="{{asset('assets/vendor/js/bootstrap.js')}}"></script>
@endsection