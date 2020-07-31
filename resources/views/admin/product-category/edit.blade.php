@extends('layouts.admin')

@section('meta-title', __('Edit Category'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-dark">
                <div class="card-header">
                    <h5 class="d-inline-block mt-2">{{ __('Edit Category') }}</h5>
                    <a href="{{ route('admin.product-category.index') }}"
                       class="float-right btn btn-primary">{{ __('View All') }}</a>
                </div>
                <form action="{{ route('admin.product-category.update', $productCategory->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $productCategory->name }}" placeholder="Name">
                        </div>
                        @error('name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror

                        <div class="row">
                            <div class="col-xl-10">
                                <div class="form-group">
                                    <label for="exampleInputFile">Thumbnail</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="thumbnail" id="thumbnail">
                                            <label class="custom-file-label" for="thumbnail">Choose file</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="">Upload</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-2">
                                <div class="form-group text-right">
                                    @if($productCategory->thumbnail)
                                        <img src="{{ asset($productCategory->thumbnail) }}" alt="{{ asset($productCategory->name) }}" style="width: 80px;">
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option {{ $productCategory->status == 1 ? 'selected="selected"' : '' }} value="1">Active</option>
                                <option {{ $productCategory->status == 0 ? 'selected="selected"' : '' }} value="0">Inactive</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control text-editor">{{ $productCategory->description }}</textarea>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success text-uppercase py-2 px-4 font-weight-bold">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
