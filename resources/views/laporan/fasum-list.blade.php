@extends('layouts.app')
@section('title')
Buat Laporan
@endsection

@section('content')
<div class="container">
    <h1>Daftar Fasilitas Umum</h1>

    <div class="row">
        @foreach($fasums as $fasum)
            <div class="col-md-4">
                <div class="card mb-3">
                    <img src="{{ asset('fasum-images/' . $fasum->image) }}" class="card-img-top" alt="{{ $fasum->nama }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $fasum->nama }}</h5>
                        <a href="{{ route('laporan.addToSession', $fasum->id) }}" class="btn btn-primary">
                            Buat Laporan
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
