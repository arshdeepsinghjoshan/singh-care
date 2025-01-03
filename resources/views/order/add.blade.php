@extends('layouts.master')
@section('title', 'Order Create')

@section('content')
<x-a-breadcrumb :columns="[
        [
            'url' => '/',
            'label' => 'Home',
        ],
        [
            'url' => 'order',
            'label' => 'Orders',
        ],
    ]" />
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-6 mb-4 order-0">
            <div class="card">

                <h5 class="card-header">{{ __('Product Lists') }}</h5>

                <div class="card-body">
                    <div class="table-responsive">
                        <x-a-grid-view :id="'order_product_table'" :model="$model" :url="'product/get-list'" :columns="[
                                'select',
                                'name',
                                'description',
                                'price',
                            ]" />
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-4 order-0">
            <div class="card">

                <h5 class="card-header">{{ __('Cart Lists') }}</h5>

                <div class="card-body">
                    <div class="table-responsive">
                        <x-a-grid-view :id="'cart_list'" :model="$model" :url="'cart/get-list'"
                            :filterButtonId="'order_filter_button'"
                            :customfilterIds="
                               [
                                   'start_date',
                                   'warehouse_id',
                               ]"
                            :columns="[
                                'select',
                                'product_name',
                                'total_price',
                                'unit_price',
                            ]" />
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection