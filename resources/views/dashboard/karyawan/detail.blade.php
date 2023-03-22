@extends('dashboard.layouts.master')
@section('title')
    {{ $title }} - {{ $user->name }}
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
                        <option value="{{ route('karyawan',$karyawan->username) }}" {{ ($user->id == $karyawan->id) ? 'selected' : '' }}>{{ $karyawan->name }}</option>
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
                <h4 class="card-title">{{ $title }} | {{ $user->name }}</h4>
                <div class="row">
                    <div class="col-md-6 text-center mb-4">
                        <img src="@if(empty($user->photo)) {{ asset('theme/template/images/user.png') }} @else {{ asset($user->photo) }} @endif" style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%" alt="people">
                        <div class="mt-3">
                            <button type="button" class="btn {{ $user->is_verifikasi == 1 ? 'btn-success' : 'btn-danger' }} btn-sm">{{ $user->is_verifikasi == 1 ? 'Sudah diverifikasi' : 'Belum diverifikasi' }}</button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm" style="width:100%">
                            <tbody>
                                <tr>
                                    <td style="width: 150px">Nama Lengkap</td>
                                    <td>{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <td>Nama Panggilan</td>
                                    <td>{{ $user->short_name }}</td>
                                </tr>
                                <tr>
                                    <td>NIK</td>
                                    <td>{{ $user->nik }}</td>
                                </tr>
                                <tr>
                                    <td>No. HP/Whatsapp</td>
                                    <td>{{ $user->phone }}</td>
                                </tr>
                                <tr>
                                    <td>Dari PT.</td>
                                    <td>{{ $user->company_name }}</td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td>{{ $user->email }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection