@extends('dashboard.layouts.master')
@section('title')
    {{ $title }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card tale-bg">
            <div class="card-people mt-auto">
                <img src="@if(empty($profile->photo)) {{ asset('theme/template/images/user.png') }} @else {{ asset($profile->photo) }} @endif" style="object-fit: cover" alt="people">
                <div class="weather-info" style="background-color: white; padding: 10px 10px 0px 0px; border-radius: 10px;">
                    <div class="d-flex">
                        <div class="ml-2">
                            <h4 class="location font-weight-bold">{{ $profile->name }}</h4>
                            <h6 class="font-weight-normal">{{ $profile->company_name }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-9 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ $title }}</h4>
                @include('dashboard.layouts.session')
                <form class="forms-sample" method="POST" action="{{ route('profile.update',$profile->id) }}" enctype="multipart/form-data" id="profile">
                    @csrf
                    {{ method_field('PUT') }}
                    <div id="profile">
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
                        <div class="form-group">
                            <label for="">Nama Lengkap</label>
                            <input type="text" class="form-control" name="name" value="{{ $profile->name }}" placeholder="Nama Lengkap">
                        </div>
                        @if (\Auth::user()->role_id == 2)
                        <div class="form-group">
                            <label for="">Nama Panggilan</label>
                            <input type="text" class="form-control" name="short_name" value="{{ $profile->short_name }}" placeholder="Nama Panggilan">
                        </div>
                        <div class="form-group">
                            <label for="">NIK</label>
                            <input type="number" class="form-control" name="nik" value="{{ $profile->nik }}" placeholder="Nomor Induk Kependudukan">
                        </div>
                        <div class="form-group">
                            <label for="">No. Telp/HP</label>
                            <input type="number" class="form-control" name="phone" value="{{ $profile->phone }}" placeholder="No. Telp/HP">
                        </div>
                        <div class="form-group">
                            <label for="">Nama Asal PT</label>
                            <input type="text" class="form-control" name="company_name" value="{{ $profile->company_name }}" placeholder="Nama Asal PT">
                        </div>
                        @endif
                        <div class="form-group">
                            <label for="">Email address</label>
                            <input type="email" class="form-control" name="email" value="{{ $profile->email }}" placeholder="Email">
                        </div>
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
                        <button type="submit" class="btn btn-primary mr-2">Submit</button>
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