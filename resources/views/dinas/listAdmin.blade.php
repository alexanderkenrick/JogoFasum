@extends('layouts.app')
@section('title')
    Daftar Admin
@endsection

@section('content')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Dinas /</span> Daftar Akun Admin</h4>
    <div class="card">
        <h5 class="card-header">Tabel Akun</h5>
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Dinas</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                    @foreach($users as $user)
                        <tr>
                            <td>{{$user->name}}</td>
                            <td>{{$user->dinas->nama}}</td>
                            <td>
                                <button type="button" class="btn btn-icon btn-warning" onclick="window.location.href='{{ route('dinas.show-detal-admin', ['id'=>$user->id]) }}'">
                                    <span class="bx bx-edit-alt me-1"></span>
                                </button>
                                <form method="POST" action="{{route('dinas.delete-user-admin', ['id'=>$user->id]) }}">
                                    @method('DELETE')
                                    @csrf
                                    <button type="submit" class="btn btn-icon btn-danger">
                                        <span class="bx bx-trash me-1"></span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        <div class="card-footer">
            {{$users->links('pagination::bootstrap-5')}}
        </div>
    </div>
    <script src="{{asset('assets/vendor/js/bootstrap.js')}}"></script>
@endsection

