@extends('layouts.app')
@section('title')
Buat Laporan
@endsection

@section('content')
<div class="container">
    <h1>Laporan</h1>
    @if (session('status'))
        <div class="box success-box">
        {{ session('status') }}
        </div>
    @endif
    @if (count($fasums) > 0)
        {{-- next slide --}}
    @else
    <div class="box info-box">
        Belum ada laporan yang dibuat
    </div>
    @endif

    <form action="{{ route('laporan.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="subject" class="form-label">Subject</label>
            <input type="text" name="subject" id="subject" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit Laporan</button>
    </form>
</div>
@endsection
