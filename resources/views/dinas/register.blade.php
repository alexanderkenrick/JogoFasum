@extends('layouts.app')
@section('title')
    Buat Akun Baru
@endsection
@section('head')

@endsection

@section('content')

    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Dinas /</span> Buat Akun Baru</h4>
    <div class="card">
        <div class="card-header">
            <h5 class="fw-bold">Buat Akun Baru</h5>
            @if(session('status'))
                <div class="alert alert-{{session('status')['status']}} alert-dismissible" role="alert">
                    {{session('status')['message']}}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>
        <div class="card-body">
            <form method="post" action="{{route('dinas.create-user')}}">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" required>
                    @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" required>
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="kota" class="form-label">Dinas</label>
                    <select class="form-select select2" name="dinas" required>
                        <option selected disabled>-- Pilih dinas --</option>
                        @foreach($dinases as $dinas)
                            <option value="{{$dinas->id}}">{{$dinas->nama}}</option>
                        @endforeach
                    </select>
                    @error('kota')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="role" class="form-label">Peran</label>
                    <select class="form-control" name="role" required>
                        <option selected disabled>-- Pilih peran --</option>
                        <option value="admin">Admin</option>
                        <option value="pimpinan">Pimpinan</option>
                    </select>
                    @error('role')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="mb-3 form-password-toggle">
                    <label class="form-label" for="password">Password</label>
                    <div class="input-group input-group-merge">
                        <input
                            type="password"
                            id="password"
                            class="form-control @error('password') is-invalid @enderror"
                            name="password"
                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                            aria-describedby="password"
                            autocomplete="new-password"
                            required
                        />
                        <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                    </div>
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <button class="btn btn-primary d-grid w-100" type="submit">Daftarkan</button>
            </form>
        </div>

    </div>

    <script>

    </script>

    <script src="{{asset('assets/vendor/js/bootstrap.js')}}"></script>
@endsection

