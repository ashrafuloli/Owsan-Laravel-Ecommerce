@extends('layouts.admin')

@section('meta-title', __('Product Categories'))

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-dark">
                <div class="card-header">
                    <h5 class="d-inline-block mt-2">{{ __('All product categories') }}</h5>
                    <a href="{{ route('admin.product-category.create') }}"
                       class="float-right btn btn-primary">{{ __('Add New') }}</a>
                </div>
                <div class="card-body">

                    <div class="btn-group mb-3">
                        <a href="{{ route('admin.product-category.index') . '?type=all' }}"
                           class="btn btn-outline-dark {{ request()->get('type') == 'all' ? 'active' : '' }}">{{ __('All') }}</a>
                        <a href="{{ route('admin.product-category.index') }}"
                           class="btn btn-outline-dark {{ request()->has('type') == '' ? 'active' : '' }}">{{ __('Active') }}</a>
                        <a href="{{ route('admin.product-category.index') . '?type=trash' }}"
                           class="btn btn-outline-dark {{ request()->get('type') == 'trash' ? 'active' : '' }}">{{ __('Trashed')}}</a>
                    </div>

                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th style="width: 80px; text-align: center">Thumbnail</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Status</th>
                            <th style="width: 150px">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($productCategories as $productCategory)
                            <tr>
                                <td>{{ $productCategory->id }}</td>
                                <td>
                                    @if($productCategory->thumbnail)
                                        <img src="{{ asset($productCategory->thumbnail) }}"
                                             alt="{{ asset($productCategory->name) }}" style="width: 80px;">
                                    @else
                                        <img src="http://placehold.jp/80x80.png" style="width: 80px;">
                                    @endif
                                </td>
                                <td>{{ $productCategory->name }}</td>
                                <td>{{ $productCategory->slug }}</td>
                                <td>
                                    <div
                                        class="badge badge-@if($productCategory->status == true ){{'success'}}@else{{'warning'}} @endif">
                                        {{ $productCategory->status_text }}
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('admin.product-category.show', $productCategory->id) }}"
                                       class="btn btn-sm btn-success"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('admin.product-category.edit', $productCategory->id) }}"
                                       class="btn btn-sm btn-info"><i class="far fa-edit"></i></a>
                                    <form action="{{ route('admin.product-category.destroy', $productCategory->id ) }}"
                                          method="POST" class="d-inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
