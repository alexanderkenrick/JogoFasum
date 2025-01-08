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
                    <img src="{{ asset($fasum->image_path) }}" class="card-img-top" alt="{{ $fasum->nama }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $fasum->nama }}</h5>
                        <form action="{{ route('laporan.addToSession', $fasum->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <textarea name="report" class="form-control" placeholder="Tulis deskripsi..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Buat Laporan</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection


