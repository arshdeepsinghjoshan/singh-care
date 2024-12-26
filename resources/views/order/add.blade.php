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
        <div class="table-responsive">
            <div class="col-lg-12 mb-4 order-0">
                <div class="card">
                <h5 class="card-header">{{ __('Product List') }}</h5>

                    <div class="card-header">
                        <x-a-grid-view :id="'product_table'" :model="$model" :url="'product/get-list/'" :columns="[
                                'id',
                                'name',
                                'created_at',
                                'created_by',
                                'action',
                            ]" />
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <div class="card-header">
                    <h3>
                        @empty($model->exists)
                        {{ __('Add') }}
                        @else
                        {{ __('Update') }}
                        @endempty
                    </h3>
                </div>
                <div class="card-body">


                    @include('order._form')
                </div>
            </div>
        </div>

    </div>
</div>
@endsection