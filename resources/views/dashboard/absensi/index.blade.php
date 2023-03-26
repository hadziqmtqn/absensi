@extends('dashboard.layouts.master')
@section('title')
    {{ $title }}
@endsection
@section('content')
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Data Filter</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Pilih Tanggal Absensi</label>
                                <input type="date" id="filter-waktuabsen" class="form-control filter" placeholder="Masukkan Tanggal">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ $title }}</h4>
                    <table id="laravel_datatable" class="display expandable-table nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Lengkap</th>
                                <th>Absensi Pada</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="{{ asset('theme/template/js/dashboard/new_absensi.js') }}"></script>
@include('dashboard.absensi.validation')
<script>
    $(function () {
        $('.absensiout').click(function(){
            swal("Opps!", "Sekarang bukan waktu absensi!", "warning");
        });
    })
</script>
@endsection