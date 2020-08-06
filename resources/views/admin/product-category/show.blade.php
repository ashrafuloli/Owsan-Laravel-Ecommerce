@extends('layouts.admin')

@section('meta-title', $productCategory->name)

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-dark">
                <div class="card-header">
                    <h5 class="d-inline-block mt-2">{{ $productCategory->name }}</h5>
                    <a href="{{ route('admin.product-category.index') }}"
                       class="float-right btn btn-primary">{{ __('View All') }}</a>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <b>{{ __('Name') }}</b>: {{ $productCategory->name }}
                        </li>
                        <li class="list-group-item">
                            <b>{{ __('Slug') }}</b>: {{ $productCategory->slug }}
                        </li>
                        <li class="list-group-item">
                            <b>{{ __('Description') }}</b>: {!! $productCategory->description !!}
                        </li>
                        <li class="list-group-item">
                            <b>{{ __('Thumbnail') }}</b>: <br>
                            <img width="100" src="{{ asset($productCategory->thumbnail) }}" alt="{{ $productCategory->name }}">
                        </li>
                        <li class="list-group-item">
                            <b>{{ __('Status') }}</b>: {{ $productCategory->status_text }}
                        </li>
                    </ul>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.product-category.edit', $productCategory->id ) }}" class="btn btn-primary text-uppercase py-2 px-4 font-weight-bold">
                        {{ __('Edit Product Category') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
