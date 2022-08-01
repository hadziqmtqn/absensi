@extends('dashboard.layouts.master')
@section('title')
    {{ $title }} - {{ $profile->name }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ $title }} | {{ $profile->name }}</h4>
                <div class="text-center">
                    <img src="@if(empty($profile->photo)) {{ asset('theme/template/images/user.png') }} @else {{ asset($profile->photo) }} @endif" style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%" alt="people">
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
                                    <input type="text" class="form-control" name="short_name" value="{{ !is_null($profile->karyawan_r) ? $profile->karyawan_r->short_name : null }}" placeholder="Nama Panggilan">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">NIK</label>
                                    <input type="number" class="form-control" name="nik" value="{{ !is_null($profile->karyawan_r) ? $profile->karyawan_r->nik : null }}" placeholder="Nomor Induk Kependudukan">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">No. Telp/HP</label>
                                    <input type="number" class="form-control" name="phone" value="{{ !is_null($profile->karyawan_r) ? $profile->karyawan_r->phone : null }}" placeholder="No. Telp/HP">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Nama Asal PT</label>
                                    <input type="text" class="form-control" name="company_name" value="{{ !is_null($profile->karyawan_r) ? $profile->karyawan_r->company_name : null }}" placeholder="Nama Asal PT">
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
                        <button type="submit" class="btn btn-primary mr-2 pull-right">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    @include('dashboard.profile.validation')
@endsection