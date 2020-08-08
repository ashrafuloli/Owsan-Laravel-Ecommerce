@extends('layouts.admin')

@section('meta-title', __('Edit Product'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-dark">
                <div class="card-header">
                    <h5 class="d-inline-block mt-2">{{ __('Edit Product') }}</h5>
                    <a href="{{ route('admin.product.index') }}"
                       class="float-right btn btn-primary">{{ __('View All') }}</a>
                </div>
                <form action="{{ route('admin.product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $product->name }}" placeholder="Name">
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
                                    <img src="{{ asset($product->default_thumbnail) }}" alt="{{ asset($product->name) }}" style="width: 80px;">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option {{ $product->status == 1 ? 'selected="selected"' : '' }} value="1">Active</option>
                                <option {{ $product->status == 0 ? 'selected="selected"' : '' }} value="0">Inactive</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control text-editor">{{ $product->description }}</textarea>
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
