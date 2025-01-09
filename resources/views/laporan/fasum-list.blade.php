@extends('layouts.app')
@section('title')
Buat Laporan
@endsection

@section('content')
<div class="container">
    <h1>Daftar Fasilitas Umum</h1>
    @if(session('status'))
        <div class="alert alert-{{ session('status') == 'success' ? 'success' : 'danger' }}">
            {{ session('message') }}
        </div>
    @endif
    <div class="row">
        @php
            $existingFasums = session('fasums');
        @endphp
        @foreach($fasums as $fasum)
            @if($existingFasums && isset($existingFasums[$fasum->id]) && $fasum->id == $existingFasums[$fasum->id])
            @else
                <div class="col-md-4">
                    <div class="card mb-3">
                        <img src="{{ asset('/fasum/'.$fasum->image_path) }}" class="card-img-top" alt="{{ $fasum->nama }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $fasum->nama }}</h5>
                            <form action="{{ route('laporan.addToSession', $fasum->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary">Buat Laporan</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</div>
<script src="{{asset('assets/vendor/js/bootstrap.js')}}"></script>
@endsection


