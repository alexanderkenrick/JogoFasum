@extends('layouts.app')
@section('title')
Fasum
@endsection

@section('content')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Fasum /</span> Dashboard</h4>
    <div class="card">
        <div class="d-flex align-items-center justify-content-between">
            <h5 class="card-header">Tabel Akun</h5>
            <button type="button" class="btn btn-primary mx-5" onclick="window.location.href='{{ route('dinas.create-fasum') }}'">
                <span class="tf-icons bx bx-plus"></span>&nbsp; Tambah Fasum
            </button>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                <tr>
                    <th>Gambar</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Dinas</th>
                </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                @foreach($fasums as $fasum)
                    <tr>
                        <td><img src="{{asset('fasum/'.$fasum->image_path)}}" class="image"></td>
                        <td>{{$fasum->nama}}</td>
                        <td>
                            @foreach($fasum->kategori as $kategori)
                                <span class="badge bg-secondary">{{$kategori->nama}}</span>
                            @endforeach
                        </td>
                        <td>{{$fasum->dinas->nama}}</td>
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

