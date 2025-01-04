@extends('layouts.master')
@section('title', 'Order Create')

@section('content')
    <style type="text/css">
        input#form1 {
            width: 72px !important;

        }

        .col-sm-7.text-center {
            width: 37.333333% !important;
        }
    </style>
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
                            <x-a-grid-view :id="'order_product_table'" :model="$model" :url="'product/get-list'" :columns="['select', 'name', 'description', 'price']" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4 order-0">
                <div class="card">

                    <h5 class="card-header">{{ __('Cart Lists') }}</h5>

                    <div class="card-body">
                        <div class="table-responsive">
                            <x-a-grid-view :id="'cart_list'" :model="$model" :url="'cart/get-list'" :filterButtonId="'order_filter_button'"
                                :customfilterIds="['start_date', 'warehouse_id']" :columns="['select', 'product_name', 'total_price', 'unit_price']" />

                        </div>
                    </div>
                </div>
                <div class="card mt-4">

                    <h5 class="card-header">{{ __('Checkout') }}</h5>

                    <div class="card-body">
                        <div class="table-responsive">
                            <x-a-grid-view :id="'cart_checkout'" :model="$model" :url="'cart/get-list/1/'" :filterButtonId="'order_filter_button'"
                                :customfilterIds="['start_date', 'warehouse_id']" :columns="['total_checkout_amount']" :paging="false" :searching="false"
                                :info="false" />
                        </div>
                        <div class="text-end mt-3">
                            <button type="button" id="placeOrder" class="btn btn-primary">Place Order</button>


                        </div>
                    </div>

                </div>


            </div>
        </div>

    @endsection
