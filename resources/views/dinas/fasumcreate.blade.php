@extends('layouts.app')
@section('title')
Buat Fasum
@endsection

@section('content')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Fasum /</span> Buat Fasilitas Umum</h4>
    <div class="card">
        <div class="card-header">
            <h5 class="fw-bold">Tambah Fasilitas Umum</h5>
            @if(session('status'))
                <div class="alert alert-{{session('status')['status']}} alert-dismissible" role="alert">
                    {{session('status')['message']}}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>
        <div class="card-body">
            <form method="post" enctype="multipart/form-data" action="{{route('dinas.store-fasum')}}">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" required>
                    @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="categories" class="form-label">Kategori <sub>(CTRL + click pada kategori)</sub></label>
                    <select multiple class="form-select" id="kategori" aria-label="kategori" name="categories[]">
                        @foreach($kategories as $kategori)
                            <option value="{{$kategori->id}}">{{$kategori->nama}}</option>
                        @endforeach
                    </select>
                    @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="luas" class="form-label">Luas (M <sup>2</sup>)</label>
                    <input type="number" class="form-control @error('luas') is-invalid @enderror" id="luas" name="luas" step="0.01" required>
                    @error('luas')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="kondisi" class="form-label">Kondisi</label>
                    @php
                        $kondisis = ['Baik', 'Rusak Ringan', 'Rusak Berat'];
                    @endphp
                    <select class="form-select select2" name="kondisi" required>
                        <option selected disabled>-- Pilih kondisi --</option>
                        @foreach($kondisis as $kondisi)
                            <option value="{{$kondisi}}">{{$kondisi}}</option>
                        @endforeach
                    </select>
                    @error('kondisi')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="asal_fasilitas" class="form-label">Sumber Dana</label>
                    @php
                        $apbns = ['APBN', 'APBD','Swasta'];
                    @endphp
                    <select class="form-select select2" name="asal_fasilitas" required>
                        <option selected disabled>-- Pilih sumber dana --</option>
                        @foreach($apbns as $apbn)
                            <option value="{{$apbn}}">{{$apbn}}</option>
                        @endforeach
                    </select>
                    @error('asal_fasilitas')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="row mb-3">
                    <div class="col-12 col-md-6 mb-3 mb-md-0">
                        <label for="lat" class="form-label">Latitude</label>
                        <input type="number" class="form-control @error('lat') is-invalid @enderror" id="lat" name="lat" step="0.0000001" required>
                        @error('lat')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                         </span>
                        @enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="long" class="form-label">Longitude</label>
                        <input type="number" class="form-control @error('long') is-invalid @enderror" id="long" name="long" step="0.0000001" required>
                        @error('long')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                         </span>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="image">image</label>
                    <input class="form-control" type="file" id="formFile" name="image" required>
                </div>

                <button class="btn btn-primary d-grid w-100" type="submit">Daftarkan</button>
            </form>
        </div>
    </div>

    <script src="{{asset('assets/vendor/js/bootstrap.js')}}"></script>
@endsection

