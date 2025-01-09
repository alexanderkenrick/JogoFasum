@extends('layouts.app')
@section('title')
    Kategori
@endsection
@section('head')

@endsection

@section('content')

    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Kategori / Daftar Kategori</span></h4>
    @if(session('status'))
        <div class="alert alert-{{session('status')['status']}} alert-dismissible" role="alert">
            {{session('status')['message']}}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="row mb-4">
        <div class="col-12 col-md-8">
            <div class="card">
                <h5 class="card-header">Tabel Kategori</h5>
                <div class="table-responsive text-nowrap">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Nama</th>
                        </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                        @foreach($kategoris as $kategori)
                            <tr>
                                <td>{{$kategori->nama}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{$kategoris->links('pagination::bootstrap-5')}}
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="fw-bold">Buat Kategori Baru</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="{{route('kategori.store')}}">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Kategori</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" required>
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                            @enderror
                        </div>

                        <button class="btn btn-primary d-grid w-100" type="submit">Tambahkan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="{{asset('assets/vendor/js/bootstrap.js')}}"></script>
@endsection

