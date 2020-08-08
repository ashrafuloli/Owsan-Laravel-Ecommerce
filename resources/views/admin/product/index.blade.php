@extends('layouts.admin')

@section('meta-title', __('Product Categories'))

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-dark">
                <div class="card-header">
                    <h5 class="d-inline-block mt-2">{{ __('All product categories') }}</h5>
                    <a href="{{ route('admin.product.create') }}"
                       class="float-right btn btn-primary">{{ __('Add New') }}</a>
                </div>
                <div class="card-body">

                    <div class="d-flex justify-content-between">
                        <div class="bulk-action-area mb-3">
                            <div class="bulk-select d-inline-block">
                                <div class="form-inline">
                                    <div class="form-group mr-3">
                                        <select name="dropdown-action" id="dropdown-action" class="form-control">
                                            <option>{{ __('Select Action') }}</option>
                                            <option value="bulk-delete">{{ __('Delete') }}</option>
                                            <option value="bulk-force-delete">{{ __('Permanent Delete') }}</option>
                                            <option value="bulk-restore">{{ __('Restore') }}</option>
                                            <option value="bulk-active">{{ __('Make active') }}</option>
                                            <option value="bulk-inactive">{{ __('Make inactive') }}</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <button id="delete-action"
                                                class="btn d-inline-block btn-primary">{{ __('Submit')}}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="btn-group mb-3">
                            <a href="{{ route('admin.product.index') . '?type=all' }}"
                               class="btn btn-outline-dark {{ request()->get('type') == 'all' ? 'active' : '' }}">{{ __('All') }}</a>
                            <a href="{{ route('admin.product.index') }}"
                               class="btn btn-outline-dark {{ request()->has('type') == '' ? 'active' : '' }}">{{ __('Active') }}</a>
                            <a href="{{ route('admin.product.index') . '?type=trash' }}"
                               class="btn btn-outline-dark {{ request()->get('type') == 'trash' ? 'active' : '' }}">{{ __('Trashed')}}</a>
                        </div>
                    </div>


                    @if(count($products))
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
                            @foreach($products as $product)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="bulk-item-id" data-id="{{ $product->id }}"
                                               name="categories[]">
                                    </td>
                                    <td>
                                        <img src="{{ asset($product->default_thumbnail) }}"
                                             alt="{{ asset($product->name) }}" style="width: 80px;">
                                    </td>
                                    <td>{{ $product->title }}</td>
                                    <td>{{ $product->slug }}</td>
                                    <td>
                                        <div
                                            class="badge badge-@if($product->status == true ){{'success'}}@else{{'warning'}} @endif">
                                            {{ $product->status_text }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($product->deleted_at !== null)
                                            <form
                                                action="{{ route('admin.product.restore', $product->id ) }}"
                                                method="POST" class="d-inline-block">
                                                @csrf
                                                @method('PUT')
                                                <button class="btn btn-sm btn-danger"><i class="fas fa-recycle"></i>
                                                </button>
                                            </form>

                                            <form
                                                action="{{ route('admin.product.force_delete', $product->id ) }}"
                                                method="POST" class="d-inline-block">
                                                @csrf
                                                @method('Delete')
                                                <button class="btn btn-sm btn-danger"
                                                        title="{{ __('Permanent Delete') }}">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('admin.product.show', $product->id) }}"
                                               class="btn btn-sm btn-success"><i class="fas fa-eye"></i></a>
                                            <a href="{{ route('admin.product.edit', $product->id) }}"
                                               class="btn btn-sm btn-info"><i class="far fa-edit"></i></a>

                                            <form
                                                action="{{ route('admin.product.destroy', $product->id ) }}"
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
                <div class="card-footer text-right">
                    <div class="d-inline-block">
                        {{ $products->links() }}
                    </div>
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
                            axios.post("{{ route('admin.product.bulk_delete') }}", {
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
                            axios.post("{{ route('admin.product.bulk_force_delete') }}", {
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
                            axios.post("{{ route('admin.product.bulk_restore') }}", {
                                item_ids
                            })
                                .then(response => {
                                    if (response.data.message == 'success') {
                                        window.location.href = window.location.href
                                    }
                                })
                                .catch(error => console.log(error))
                        }
                    } else if ($('#dropdown-action').val() == 'bulk-active') {

                        if (item_ids.length > 0) {
                            axios.post("{{ route('admin.product.bulk_active') }}", {
                                item_ids
                            })
                                .then(response => {
                                    if (response.data.message == 'success') {
                                        window.location.href = window.location.href
                                    }
                                })
                                .catch(error => console.log(error))
                        }

                    } else if ($('#dropdown-action').val() == 'bulk-inactive') {

                        if (item_ids.length > 0) {
                            axios.post("{{ route('admin.product.bulk_inactive') }}", {
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
                });
            })
        })(jQuery)
    </script>
@endsection
