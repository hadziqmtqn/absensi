@extends('dashboard.layouts.master')
@section('title')
    {{ $title }}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                    <h3 class="font-weight-bold">Welcome {{ \Auth::user()->name }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card tale-bg">
                <div class="card-people mt-auto">
                    <img src="{{ asset('theme/template/images/net.png') }}" alt="people">
                    <div class="weather-info">
                        <div class="d-flex">
                            <div>
                                {{-- <h2 class="mb-0 font-weight-normal"><i class="icon-sun mr-2"></i>31<sup>C</sup></h2> --}}
                            </div>
                            <div class="ml-2">
                                @if($currentUserInfo)
                                    <h4 class="location font-weight-normal">{{ $currentUserInfo->cityName }}</h4>
                                    <h6 class="font-weight-normal">{{ $currentUserInfo->countryName }}</h6>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 grid-margin transparent">
            <div class="row">
                <div class="col-md-6 mb-4 stretch-card transparent">
                    <div class="card card-tale">
                        <div class="card-body">
                            <p class="mb-4">Pasang Baru</p>
                            <p class="fs-30 mb-2">{{ $pasangBaruToday }}</p>
                            <p>Hari Ini</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4 stretch-card transparent">
                    <div class="card card-dark-blue">
                        <div class="card-body">
                            <p class="mb-4">Job Baru</p>
                            <p class="fs-30 mb-2">{{ $dataJobToday }}</p>
                            <p>Hari Ini</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-4 mb-lg-0 stretch-card transparent">
                    <div class="card card-light-blue">
                        <div class="card-body">
                            <p class="mb-4">Absensi Karyawan</p>
                            <p class="fs-30 mb-2">{{ $absensiToday }}</p>
                            <p>Hari Ini</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 stretch-card transparent">
                    <div class="card card-light-danger">
                        <div class="card-body">
                            <p class="mb-4">Total Karyawan</p>
                            <p class="fs-30 mb-2">{{ $totalKaryawan }}</p>
                            <p>Teknisi</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <p class="card-title">Statistik Pelanggan Baru</p>
                    <p class="font-weight-500">Statistik pelanggan baru setiap bulan dalam 1 tahun</p>
                    <canvas id="pasangBaru"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <p class="card-title">Statistik Data Job</p>
                    </div>
                    <p class="font-weight-500">Statistik data job setiap bulan dalam 1 tahun</p>
                    <canvas id="dataJob"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @include('dashboard.dashboard.chart-pasangbaru')
    @include('dashboard.dashboard.chart-datajob')
@endsection