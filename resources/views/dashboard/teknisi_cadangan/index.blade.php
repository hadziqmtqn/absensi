@extends('dashboard.layouts.master')
@section('title')
    {{ $title }}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ $title }}</h4>
                    <table id="laravel_datatable" class="display expandable-table nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Teknisi</th>
                                <th>Dibuat pada</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('theme/template/js/dashboard/teknisi-cadangan.js') }}"></script>
@endsection