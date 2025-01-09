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

        <form action="{{ route('laporan.store') }}" method="POST" enctype="multipart/form-data">
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
                                <img src="{{ asset('/fasum/'.$fasum->image_path) }}" class="card-img-top" alt="{{ $fasum->nama }}">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $fasum->nama }}</h5>
                                    <div class="mb-1">
                                        <input type="hidden" name="fasums[{{$index}}][id]" value="{{$fasum->id}}">
                                        <textarea name="fasums[{{$index}}][deskripsi]" class="form-control"
                                                  placeholder="Tulis deskripsi..." required></textarea>
                                    </div>
                                </div>
                                <div class="mb-3 px-2">
                                    <input type="file" name="fasums[{{$index}}][image]" class="form-control" required>
                                </div>
                                <div class="mb-3 px-2">
                                    <button type="button" class="btn btn-danger" onclick="deleteFromSession({{$fasum->id}})">Batalkan</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="box info-box">
                        Belum ada fasilitas umum yang dimuat <br>
                        <a href="{{ route('laporan.fasumList') }}" class="btn btn-primary">Pilih fasilitas umum</a>
                    </div>
                @endif
            </div>
            @if(count($fasumArr) > 0)
                <button type="submit" class="btn btn-primary">Submit Laporan</button>
            @endif
        </form>

    </div>
    <script>
        function deleteFromSession(id) {
            $.ajax({
                url: "{{ route('laporan.deleteFromSession') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id
                },
                success: function (response) {
                    location.reload();
                }
            });
        }
    </script>
    <script src="{{asset('assets/vendor/js/bootstrap.js')}}"></script>
@endsection
