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

                    <div class="d-flex justify-content-between">
                        <div class="bulk-action-area mb-3">
                            <div class="bulk-select d-inline-block">
                                <div class="form-inline">
                                    <div class="form-group mr-3">
                                        <select name="dropdown-action" id="dropdown-action" class="form-control">
                                            <option>Select action</option>
                                            <option value="bulk-delete">Delete</option>
                                            <option value="bulk-force-delete">Permanent Delete</option>
                                            <option value="bulk-restore">Restore</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <button id="delete-action" class="btn d-inline-block btn-primary">{{ __('Submit')}}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="btn-group mb-3">
                            <a href="{{ route('admin.product-category.index') . '?type=all' }}"
                               class="btn btn-outline-dark {{ request()->get('type') == 'all' ? 'active' : '' }}">{{ __('All') }}</a>
                            <a href="{{ route('admin.product-category.index') }}"
                               class="btn btn-outline-dark {{ request()->has('type') == '' ? 'active' : '' }}">{{ __('Active') }}</a>
                            <a href="{{ route('admin.product-category.index') . '?type=trash' }}"
                               class="btn btn-outline-dark {{ request()->get('type') == 'trash' ? 'active' : '' }}">{{ __('Trashed')}}</a>
                        </div>
                    </div>


                    @if(count($productCategories))
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th style="width: 10px"><input type="checkbox" id="select-all"></th>
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
                                    <td>
                                        <input type="checkbox" class="bulk-item-id" data-id="{{ $productCategory->id }}"
                                               name="categories[]">
                                    </td>
                                    <td>
                                        <img src="{{ asset($productCategory->default_thumbnail) }}"
                                             alt="{{ asset($productCategory->name) }}" style="width: 80px;">
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
                                        @if($productCategory->deleted_at !== null)
                                            <form
                                                action="{{ route('admin.product-category.restore', $productCategory->id ) }}"
                                                method="POST" class="d-inline-block">
                                                @csrf
                                                @method('PUT')
                                                <button class="btn btn-sm btn-danger"><i class="fas fa-recycle"></i>
                                                </button>
                                            </form>

                                            <form
                                                action="{{ route('admin.product-category.force_delete', $productCategory->id ) }}"
                                                method="POST" class="d-inline-block">
                                                @csrf
                                                @method('Delete')
                                                <button class="btn btn-sm btn-danger"
                                                        title="{{ __('Permanent Delete') }}">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('admin.product-category.show', $productCategory->id) }}"
                                               class="btn btn-sm btn-success"><i class="fas fa-eye"></i></a>
                                            <a href="{{ route('admin.product-category.edit', $productCategory->id) }}"
                                               class="btn btn-sm btn-info"><i class="far fa-edit"></i></a>

                                            <form
                                                action="{{ route('admin.product-category.destroy', $productCategory->id ) }}"
                                                method="POST" class="d-inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center border-top border-secondary py-2">
                            <h6>{{ __('No Product Found') }}</h6>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        (function ($) {
            $(document).ready(function () {
                let item_ids = []
                $(document).on('click', '.bulk-item-id', function () {
                    let data_id = $(this).data('id');
                    if ($(this).prop('checked')) {
                        if (!item_ids.includes(data_id)) {
                            item_ids.push(data_id)
                        }
                    } else {
                        if (item_ids.includes(data_id)) {
                            item_ids = item_ids.filter(element => element != data_id)
                        }
                    }
                    console.log(item_ids)

                })
                // Multi select
                $('#select-all').on('click', function () {
                    if ($(this).prop('checked')) {
                        $.each($('.bulk-item-id').prop('checked', 'checked'), function () {
                            data_id = $(this).data('id')
                            if (!item_ids.includes(data_id)) {
                                item_ids.push(data_id)
                            }
                        })
                        console.log(item_ids)
                    } else {
                        $('.bulk-item-id').prop('checked', '');
                        item_ids = []
                        console.log(item_ids)
                    }
                })
                // Bulk action
                $('#delete-action').on('click', function () {
                    if ($('#dropdown-action').val() == 'bulk-delete') {

                        if (item_ids.length > 0) {
                            axios.post("{{ route('admin.product-category.bulk_delete') }}", {
                                item_ids
                            })
                                .then(response => {
                                    if (response.data.message == 'success') {
                                        window.location.href = window.location.href
                                    }
                                })
                                .catch(error => console.log(error))
                        }

                    } else if ($('#dropdown-action').val() == 'bulk-force-delete') {

                        if (item_ids.length > 0) {
                            axios.post("{{ route('admin.product-category.bulk_force_delete') }}", {
                                item_ids
                            })
                                .then(response => {
                                    if (response.data.message == 'success') {
                                        window.location.href = window.location.href
                                    }
                                })
                                .catch(error => console.log(error))
                        }

                    } else if ($('#dropdown-action').val() == 'bulk-restore') {

                        if (item_ids.length > 0) {
                            axios.post("{{ route('admin.product-category.bulk_restore') }}", {
                                item_ids
                            })
                                .then(response => {
                                    if (response.data.message == 'success') {
                                        window.location.href = window.location.href
                                    }
                                })
                                .catch(error => console.log(error))
                        }

                    }
                })


            })
        })(jQuery)
    </script>
@endsection
