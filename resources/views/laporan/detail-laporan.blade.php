@extends('layouts.app')
@section('title')
    Edit Laporan
@endsection

@section('content')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Home /</span> Dashboard</h4>
    <div class="card mb-3">
        <h5 class="card-header">LAPORAN</h5>
        <div class="card-body">
            <label for="name" class="form-label">Subject</label>
            <input type="text" class="form-control form-control-lg" value="{{ $laporans->subject }}" disabled>
        </div>
    </div>

    <div class="card">
        <h5 class="card-header">Edit Laporan</h5>
        @if (session('status'))
            <div class="alert alert-{{ session('status')['status'] }} alert-dismissible" role="alert">
                {{ session('status')['message'] }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Kondisi sekarang</th>
                        <th>Nama Fasum</th>
                        <th>Deskripsi</th>
                        <th>Status</th>
                        <th>Bukti penanganan</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($laporans->fasum as $fasum)
                        <tr>
                            <td><img src="{{ asset('laporan/' . $fasum->pivot->image_path) }}" class="image"></td>
                            @php
                                $statusArr = ['Antri', 'Dikerjakan', 'Outsource', 'Selesai', 'Tidak terselesaikan'];
                                $fin_evidence_required = 'required';
                            @endphp
                            <td>{{ $fasum->nama }}</td>
                            <td>{{ $fasum->pivot->deskripsi }}</td>
                            <td>

                                <select name="status" id="status-{{ $laporans->id }}-{{ $fasum->id }}" disabled>
                                    @foreach ($statusArr as $status)
                                        <option value="{{ $status }}"
                                            {{ $fasum->pivot->status == $status ? 'selected' : '' }}>{{ $status }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                @if ($fasum->pivot->image_selesai == '')
                                    <strong>Dinas belum mengupload bukti penanganan</strong>
                                @else
                                    <img src="{{ asset('laporan/' . $fasum->pivot->image_selesai) }}" class='image'>
                                @endif
                            </td>
                        </tr>
                        @if ($fasum->pivot->status == 'Tidak terselesaikan')
                            <tr>
                                <td colspan="6">
                                    <label for="keterangan_dinas">Keterangan dari pihak kedinasan</label><br>
                                    <textarea style="width: 50vw; height: 10vh" name="keterangan_dinas" id="keterangan_dinas" cols="10" rows="10"
                                        disabled>{{ $fasum->pivot->keterangan_dinas }}</textarea>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        {{-- <div class="card-footer">
            {{$fasums->links('pagination::bootstrap-5')}}
        </div> --}}
    </div>

    <script>
        function updateStatusFasum(laporan_id, fasum_id) {
            let status = $(`#status-${laporan_id}-${fasum_id}`).val();
            console.log(status);
            $.ajax({
                type: "POST",
                url: "{{ route('dinas.update-fasum') }}",
                data: {
                    '_token': '{{ csrf_token() }}',
                    'laporan_id': laporan_id,
                    'fasum_id': fasum_id,
                    'status': status
                },
                success: function(response) {
                    alert(response.message);
                    window.location.reload();
                }
            });
        }
    </script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
@endsection
