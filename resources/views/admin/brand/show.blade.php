@extends('layouts.admin')

@section('meta-title', $brand->name)

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-dark">
                <div class="card-header">
                    <h5 class="d-inline-block mt-2">{{ $brand->name }}</h5>
                    <a href="{{ route('admin.brand.index') }}"
                       class="float-right btn btn-primary">{{ __('View All') }}</a>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <b>{{ __('Name') }}</b>: {{ $brand->name }}
                        </li>
                        <li class="list-group-item">
                            <b>{{ __('Slug') }}</b>: {{ $brand->slug }}
                        </li>
                        <li class="list-group-item">
                            <b>{{ __('Description') }}</b>: {!! $brand->description !!}
                        </li>
                        <li class="list-group-item">
                            <b>{{ __('Thumbnail') }}</b>: <br>
                            <img width="100" src="{{ asset($brand->thumbnail) }}" alt="{{ $brand->name }}">
                        </li>
                        <li class="list-group-item">
                            <b>{{ __('Status') }}</b>: {{ $brand->status_text }}
                        </li>
                    </ul>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.brand.edit', $brand->id ) }}" class="btn btn-primary text-uppercase py-2 px-4 font-weight-bold">
                        {{ __('Edit Product Brand') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
