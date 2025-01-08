@extends('layouts.app')
@section('title')
    Buat Laporan
@endsection

@section('content')
    <div class="container">
        <h2>Submit Laporan</h2>
        @if(session('status'))
            <div class="alert alert-{{ session('status') == 'success' ? 'success' : 'danger' }}">
                {{ session('message') }}
            </div>
        @endif

        <form action="{{ route('laporan.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="subject" class="form-label">Subject</label>
                <input type="text" name="subject" id="subject" class="form-control" required>
            </div>
            <div class="row">
                @if (count($fasumArr) > 0)
                    @csrf
                    @foreach($fasumArr as $index => $fasum)
                        <div class="col-md-4">
                            <div class="card mb-3">
                                <img src="{{ asset('/laporan/'.$fasum->image_path) }}" class="card-img-top" alt="{{ $fasum->nama }}">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $fasum->nama }}</h5>
                                    <div class="mb-3">
                                        <input type="hidden" name="fasums[{{$index}}][id]" value="{{$fasum->id}}">
                                        <textarea name="fasums[{{$index}}][deskripsi]" class="form-control"
                                                  placeholder="Tulis deskripsi..." required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="box info-box">
                        Belum ada fasilitas umum yang dimuat
                    </div>
                @endif
            </div>
            @if(count($fasumArr) > 0)
                <button type="submit" class="btn btn-primary">Submit Laporan</button>
            @endif
        </form>

    </div>
@endsection
