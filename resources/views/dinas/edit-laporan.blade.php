@extends('layouts.app')
@section('title')
Edit Laporan
@endsection

@section('content')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Home /</span> Dashboard</h4>
    <div class="card">
        <h5 class="card-header">Daftar Laporan</h5>

        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                <tr>
                    <th>Kondisi sekarang</th>
                    <th>Nama Fasum</th>
                    <th>Deskripsi</th>
                    <th>Status</th>
                    <th>Bukti penanganan</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                @foreach($laporans->fasum as $fasum)
                <tr>
                        <td><img src="{{asset('laporan/'.$fasum->pivot->image_path)}}" class="image"></td>
                        @php
                        $statusArr = ['Antri', 'Dikerjakan', 'Outsource', 'Selesai', 'Tidak terselesaikan'];
                        $fin_evidence_required = "required";
                        @endphp
                        <td>{{$fasum->nama}}</td>
                        <td width="20%">{{$fasum->pivot->deskripsi}}</td>
                        <td>
                            <select name="status" id="status-{{$laporans->id}}-{{$fasum->id}}" onchange="updateStatusFasum({{$laporans->id}}, {{$fasum->id}})">
                                @foreach ($statusArr as $status)
                                <option value="{{$status}}" {{$fasum->pivot->status == $status ? 'selected' : ''}}>{{$status}}</option>
                                @endforeach
                            </select>
                        </td>
                        <form action="{{ route('dinas.update-laporan') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <td>
                                @if ($fasum->pivot->image_selesai == "")
                                    <div class='mb-3'>
                                        <input type='file' id='formFile' name='image'
                                            @if ($fasum->pivot->status == 'Antri')
                                                disabled
                                            @elseif ($fasum->pivot->status == 'Dikerjakan' || $fasum->pivot->status == 'Outsource')
                                                required
                                            @endif
                                        >
                                    </div>
                                @else
                                    <img src="{{ asset('laporan/' . $fasum->pivot->image_selesai) }}" class='image'>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn btn-primary">
                                    Save
                                </button>
                            </td>
                        </form>
                </tr>
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
                url: "{{route('dinas.update-fasum')}}",
                data: {
                    '_token' : '{{csrf_token()}}',
                    'laporan_id' : laporan_id,
                    'fasum_id' : fasum_id,
                    'status' : status
                },
                success: function (response) {
                    alert(response.message);
                }
            });
        }
    </script>
    <script src="{{asset('assets/vendor/js/bootstrap.js')}}"></script>
@endsection

