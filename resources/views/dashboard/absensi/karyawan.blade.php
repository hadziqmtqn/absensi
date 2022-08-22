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
                    @include('dashboard.layouts.session')
                    @if ($jamSekarang > $awalAbsensi && $jamSekarang < $akhirAbsensi && $cekAbsensi < 1)
                    <div class="text-center">
                        <a href="{{ route('absensi.store') }}" class="btn btn-inverse-primary btn-rounded" style="padding: 30px; border-radius: 50%">
                            <i class="ti-power-off" style="font-size: 70pt"></i>
                        </a>
                    </div>
                    @elseif($jamSekarang > $awalAbsensi && $jamSekarang < $akhirAbsensi && $cekAbsensi > 0)
                    <div class="text-center">
                        <button class="btn btn-inverse-danger btn-rounded" onclick="absensi()" style="padding: 30px; border-radius: 50%">
                            <i class="ti-power-off" style="font-size: 70pt"></i>
                        </button>
                    </div>
                    @else
                    <div class="text-center">
                        <button class="btn btn-inverse-danger btn-rounded" onclick="absensiout()" style="padding: 30px; border-radius: 50%">
                            <i class="ti-power-off" style="font-size: 70pt"></i>
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    function absensi(){
        swal("Opps!", "Anda sudah mengisi absensi hari ini!", "warning");
    }
</script>
<script>
    function absensiout(){
        swal("Opps!", "Sekarang bukan waktu absensi!", "warning");
    }
</script>
@endsection