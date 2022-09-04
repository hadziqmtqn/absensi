@extends('dashboard.layouts.master')
@section('title')
    {{ $title }}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ $title }} {{ $role->name }}</h4>
                    @include('dashboard.layouts.session')
                    <form class="forms-sample" method="POST" action="{{ route('role.update',$role->id) }}">
                        @csrf
                        {{ method_field('PUT') }}
                        <div class="form-group">
                            <label for="">Nama</label>
                            <input type="text" class="form-control" name="name" value="{{ $role->name }}" placeholder="Nama Role">
                        </div>
                        <div class="form-group">
                            <label for="">Permission</label>
                            @foreach($permission as $value)
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" checked="">
                                    {{ Form::checkbox('permission[]', $value->id, in_array($value->id, $rolePermissions) ? true : false, array('class' => 'name')) }}
                                {{ $value->name }}
                                <i class="input-helper"></i></label>
                            </div>
                            @endforeach
                        </div>
                        <button type="submit" class="btn btn-primary mr-2">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection