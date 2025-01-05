@extends('layouts.app')
@section('title')
Fasum
@endsection

@section('content')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Fasum /</span> Dashboard</h4>
    <div class="card">
        <h5 class="card-header">Tabel Akun</h5>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                <tr>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Dinas</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                @foreach($fasums as $fasum)
                    <tr>
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

