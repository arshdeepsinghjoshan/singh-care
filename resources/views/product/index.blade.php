@extends('layouts.master')
@section('title', 'wallet Index')

@section('content')



    <x-a-breadcrumb :columns="[
        [
            'url' => '/',
            'label' => 'Home',
        ],
        [
            'url' => 'product',
            'label' => 'Product',
        ],
    ]" />

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12 mb-4 order-0">

                <div class="card">
                    <div class="card-header">
                        <h3>{{ __('Import') }}</h3>
                    </div>
                    <div class="card-body">
                        <form id="blog-category-form" class="row needs-validation justify-content-center"
                            Action="{{ route('product.import') }}" method="post" novalidate enctype="multipart/form-data">
                            @csrf
                            <div class="col-md-6">
                                <div class="d-md-flex align-items-start">
                                    <div class="flex-grow-1">
                                        <div class="mb-3 field-category-title required">
                                            <label class="form-label" for="category-title">Product File</label>
                                            <input type="file" id="category-title" class="form-control"
                                                value="{{ old('file') }}" id="validationCustom01" name="file">
                                        </div>
                                    </div>
                                    <div class="mt-3 mt-sm-4 text-center ml-sm-3">
                                        <button type="submit" id="blog-category-form-submit"
                                            class="btn btn-primary m-1">Save</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 mb-4 order-0">
                <div class="card">
                    <h5 class="card-header">{{ __('Index') }}</h5>
                    <div class="card-body">
                        <x-a-update-menu-items :model="$model" :action="'index'" />
                        <div class="table-responsive">
                            <x-a-grid-view :id="'product_table'" :model="$model" :url="'product/get-list/'" :columns="[
                            'select',
                                'id',
                                [
                                    'attribute' => 'batch_no',
                                    'label' => 'Batch',
                                ],
                               
                                'name',
                                'price',
                                {{-- 'mrp_price', --}}
                            
                                [
                                    'attribute' => 'hsn_code',
                                    'label' => 'HSN',
                                ],
                            
                                [
                                    'attribute' => 'expiry_date',
                                    'label' => 'EXP Date',
                                ],
                            [
                                    'attribute' => 'mfg',
                                ],
                                [
                                    'attribute' => 'agency_name',
                                    'label' => 'Agency',
                                ],
                                [
                                    'attribute' => 'bill_date',
                                    'label' => 'Bill Date',
                                ],
                            
                                'action',
                            ]" />
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <script>

$(document).ready(function () {

    // Select all checkbox handler
    $('#select-all').on('click', function() {
        $('.product-checkbox').prop('checked', this.checked);
    });

    // Delete selected
    $('.product-delete').on('click', function (e) {
        e.preventDefault();
        let selectedIds = [];
        $('.select-product:checked').each(function () {
            selectedIds.push($(this).val());
        });

        if (selectedIds.length === 0) {
            alert('Please select at least one product to delete.');
            return;
        }

        if (!confirm('Are you sure you want to delete selected products?')) {
            return;
        }

        $.ajax({
            url: '{{ route("product.bulkDelete") }}',
            type: 'DELETE',
            data: {
                ids: selectedIds,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                alert(response.message);
                location.reload(); // or remove the rows without reloading
            },
            error: function (xhr) {
                alert('An error occurred. Please try again.');
            }
        });
    });
    });
</script>
@endsection
