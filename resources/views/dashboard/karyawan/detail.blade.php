@extends('dashboard.layouts.master')
@section('title')
    {{ $title }} - {{ $profile->name }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Pilih Karyawan</h4>
                <div class="form-group">
                    <select class="form-control" onchange="location = this.value;">
                        @foreach ($listKaryawan as $karyawan)
                        <option value="{{ route('karyawan',$karyawan->username) }}" {{ ($profile->id == $karyawan->id) ? 'selected' : '' }}>{{ $karyawan->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ $title }} | {{ $profile->name }}</h4>
                <div class="text-center">
                    <img src="@if(empty($profile->photo)) {{ asset('theme/template/images/user.png') }} @else {{ asset($profile->photo) }} @endif" style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%" alt="people">
                    <p class="mt-3">
                        @if ($profile->is_verifikasi == 1)
                            <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#verifikasi">Sudah diverifikasi</button>
                        @else
                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#verifikasi">Belum diverifikasi</button>
                        @endif
                    </p>
                </div>
            </div>
            <div class="card-body">
                <hr>
                @include('dashboard.layouts.session')
                <form class="forms-sample" method="POST" action="{{ route('karyawan.update',$profile->id) }}" enctype="multipart/form-data" id="profile">
                    @csrf
                    {{ method_field('PUT') }}
                    <div id="profile">
                        <div class="row">
                            <div class="col-md-6">
                                @if (\Auth::user()->role_id == 1)
                                <div class="form-group">
                                    <label for="">Role</label>
                                    <select name="role_id" class="form-control">
                                        <option value="">Pilih</option>
                                        @foreach ($listRole as $role)
                                            <option value="{{ $role->id }}" {{ $role->id == $profile->role_id ? 'selected' : '' }}>{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Nama Lengkap</label>
                                    <input type="text" class="form-control" name="name" value="{{ $profile->name }}" placeholder="Nama Lengkap">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Nama Panggilan</label>
                                    <input type="text" class="form-control" name="short_name" value="{{ $profile->short_name }}" placeholder="Nama Panggilan">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">NIK</label>
                                    <input type="number" class="form-control" name="nik" value="{{ $profile->nik }}" placeholder="Nomor Induk Kependudukan">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">No. Telp/HP</label>
                                    <input type="number" class="form-control" name="phone" value="{{ $profile->phone }}" placeholder="No. Telp/HP">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Nama Asal PT</label>
                                    <input type="text" class="form-control" name="company_name" value="{{ $profile->company_name }}" placeholder="Nama Asal PT">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Email address</label>
                                    <input type="email" class="form-control" name="email" value="{{ $profile->email }}" placeholder="Email">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Photo</label>
                                    <input type="file" name="photo" accept=".jpg,.jpeg,.png,.svg" class="file-upload-default">
                                    <div class="input-group col-xs-12">
                                        <input type="text" class="form-control file-upload-info" disabled="" placeholder="Upload Photo">
                                        <span class="input-group-append">
                                            <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <button type="submit" class="btn btn-primary mr-2">Submit</button>
                        <a href="{{ route('karyawan',$profile->username.'/katasandi') }}" class="btn btn-inverse-danger btn-fw">Ubah Password</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <!-- Modal -->
    <div class="modal fade" id="verifikasi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cek Verifikasi</h5>
                </div>
                <div class="modal-body">
                @if ($profile->is_verifikasi == 1)
                    <h4>Apakah Anda yakin akan membatalkan verifikasi <span style="font-weight: bold">{{ $profile->name }}</span>?</h4>
                @else
                    <h4>Apakah Anda yakin akan memverifikasi <span tstyle="font-weight: bold">{{ $profile->name }}</span>?</h4>
                @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-inverse-secondary btn-fw" data-dismiss="modal">Batal</button>
                    @if ($profile->is_verifikasi == 1)
                        <a href="{{ route('karyawan',$profile->id.'/undo_verifikasi') }}" class="btn btn-success">OK. Yakin</a>
                    @else
                        <a href="{{ route('karyawan',$profile->id.'/verifikasi') }}" class="btn btn-danger">OK. Yakin</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    {{-- akhir modal --}}
    @include('dashboard.profile.validation')
@endsection