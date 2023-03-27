@extends('dashboard.layouts.master')
@section('title')
    {{ $title }} {{ $dataJob->dataPasangBaru->kode }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ $title }}</h4>
                <form class="forms-sample" method="POST" action="{{ route('data-job.update', $dataJob->id) }}" enctype="multipart/form-data" id="datajob">
                    @csrf
                    {{ method_field('PUT') }}
                    <div id="datajob">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Kode Pasang Baru</label>
                                    <select name="kode_pasang_baru" class="form-control">
                                        <option value="">Pilih</option>
                                        @foreach ($listPasangBaru as $pasang)
                                            <option value="{{ $pasang->id }}" {{ $pasang->id == $dataJob->kode_pasang_baru ? 'selected' : '' }}>{{ $pasang->id }} - {{ $pasang->kode }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Nama Karyawan</label>
                                    <select name="user_id" class="form-control">
                                        <option value="">Pilih</option>
                                        @foreach ($listAbsensi as $absensi)
                                            <option value="{{ $absensi->user_id }}" {{ $absensi->user_id == $dataJob->user_id ? 'selected' : '' }}>{{ $absensi->user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            {{-- <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Status</label>
                                    <select name="status" class="form-control">
                                        <option value="">Pilih</option>
                                        <option value="0" {{ $dataJob->dataPasangBaru->status == '0' ? 'selected' : '' }}>Waiting</option>
                                        <option value="1" {{ $dataJob->dataPasangBaru->status == '1' ? 'selected' : '' }}>Is Progress</option>
                                        <option value="2" {{ $dataJob->dataPasangBaru->status == '2' ? 'selected' : '' }}>Pending</option>
                                        <option value="3" {{ $dataJob->dataPasangBaru->status == '3' ? 'selected' : '' }}>Success</option>
                                    </select>
                                </div>
                            </div> --}}
                        </div>
                        <button {{ $dataJob->dataPasangBaru->status < 3 ? 'type="submit"' : 'type="button" disabled' }} class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@include('dashboard.data_pasang_baru.validation')
@endsection