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
                    <h6 class="font-weight-normal mb-0">All systems are running smoothly! You have <span class="text-primary">3 unread alerts!</span></h6>
                </div>
                <div class="col-12 col-xl-4">
                    <div class="justify-content-end d-flex">
                        <div class="dropdown flex-md-grow-1 flex-xl-grow-0">
                            <form action="{{ route('dashboard.index') }}" method="get">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="date" name="search" class="form-control form-control-sm" value="{{ ($search) }}">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-sm btn-primary" type="button">Search</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <p class="card-title">{{ $subTitle }}</p>
                    <table class="display expandable-table nowrap" id="myTable" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Status</th>
                                <th>Kode</th>
                                <th>Inet</th>
                                <th>Nama Pelanggan</th>
                                <th>No. HP</th>
                                <th>Alamat Pasang Baru</th>
                                <th>Tanggal</th>
                            </tr>  
                        </thead>
                        <tbody>
                            @foreach ($listJobs as $e => $job)
                                <tr>
                                    <td>{{ $e+1 }}</td>
                                    <td class="font-weight-medium">
                                        <button type="button" class="btn btn-info btn-sm btn_job" data-modal="{{ $job->id }}" style="padding: 7px 10px">Detail</button>
                                        @if($job->status == 0)
                                        <button type="button" class="btn btn-dark btn-sm" style="padding: 7px 10px">Waiting</button>
                                        @elseif($job->status == 1)
                                        <button type="button" class="btn btn-primary btn-sm" style="padding: 7px 10px">In Progress</button>
                                        @elseif($job->status == 2)
                                        <button type="button" class="btn btn-warning btn-sm" style="padding: 7px 10px">Pending</button>
                                        @else
                                        <button type="button" class="btn btn-success btn-sm" style="padding: 7px 10px">Success</button>
                                        @endif
                                    </td>
                                    <td class="font-weight-bold">{{ $job->kode }}</td>
                                    <td>{{ $job->inet }}</td>
                                    <td>{{ $job->nama_pelanggan }}</td>
                                    <td>{{ $job->no_hp }}</td>
                                    <td>{{ $job->alamat }}</td>
                                    <td>{{ \Carbon\Carbon::parse($job->create_job)->isoFormat('DD MMMM YYYY') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="{{ asset('theme/template/js/dashboard/ds-karyawan.js') }}"></script>
@foreach($listJobs as $job)
    <!-- Modal -->
    <div class="modal fade" id="modal-job-{{ $job->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Job {{ $job->kode }}</h5>
                </div>
                <div class="modal-body">
                    <ul class="icon-data-list">
                        <li>
                            <div class="d-flex">
                                <div>
                                    <p class="text-info mb-1">Kode</p>
                                    <p class="mb-0">{{ $job->kode }}</p>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="d-flex">
                                <div>
                                    <p class="text-info mb-1">Inet</p>
                                    <p class="mb-0">{{ $job->inet }}</p>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="d-flex">
                                <div>
                                    <p class="text-info mb-1">Nama Pelanggan</p>
                                    <p class="mb-0">{{ $job->nama_pelanggan }}</p>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="d-flex">
                                <div>
                                    <p class="text-info mb-1">No. HP</p>
                                    <p class="mb-0">{{ $job->no_hp }}</p>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="d-flex">
                                <div>
                                    <p class="text-info mb-1">Alamat</p>
                                    <p class="mb-0">{{ $job->alamat }}</p>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="d-flex">
                                <div>
                                    <p class="text-info mb-1">Acuan Lokasi</p>
                                    <p class="mb-0">{{ $job->acuan_lokasi }}</p>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="d-flex">
                                <div>
                                    <p class="text-info mb-1">Status</p>
                                    <p class="mb-0">
                                        @if($job->status == 0)
                                        <span class="badge badge-dark">Waiting</span>
                                        @elseif($job->status == 1)
                                        <span class="badge badge-primary">In Progress</span>
                                        @elseif($job->status == 2)
                                        <span class="badge badge-warning">Pending</span>
                                        @else
                                        <span class="badge badge-success">Success</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="d-flex">
                                <div>
                                    <p class="text-info mb-1">Photo</p>
                                </div>
                            </div>
                        </li>
                    </ul>
                    @if(!empty($job->foto))
                    <img src="{{ asset($job->foto) }}" style="width: 100%" alt="{{ $job->kode }}">
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info btn-fw" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    {{-- akhir modal --}}
@endforeach
@endsection