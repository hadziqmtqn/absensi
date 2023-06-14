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
                <form class="forms-sample" method="POST" action="{{ route('profile.password',$profile->id) }}" enctype="multipart/form-data" id="validasi">
                    @csrf
                    {{ method_field('PUT') }}
                    <div id="profile">
                        <div class="form-group">
                            <label for="">Password</label>
                            <input type="password" class="form-control" name="password" placeholder="Password">
                        </div>
                        <div class="form-group">
                            <label for="">Ulangi Password</label>
                            <input type="password" class="form-control" name="confirm_password" placeholder="Password">
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
