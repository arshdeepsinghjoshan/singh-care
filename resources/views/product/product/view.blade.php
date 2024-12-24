@extends('layouts.master')
@section('content')
<?php

use App\Models\User;
?>
<x-a-breadcrumb :columns="[
        [
            'url' => '/',
            'label' => 'Home',
        ],
        [
             'url' => 'product',
            'label' => 'Product',
        ],
        !empty($model->name) ? (strlen($model->name) > 100 ? substr($model->name, 0, 100) . '...' : $model->name) : 'N/A'
    ]" />

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <div class="card-body">
                    <h5>{{ !empty($model->name) ? (strlen($model->name) > 100 ? substr($model->name, 0, 100) . '...' : $model->name) : 'N/A' }}
                        <span class="{{ $model->getStateBadgeOption() }}">{{ $model->getState() }}</span>
                    </h5>

                    <x-a-detail-view :model="$model" :type="'double'" :column="
    [
        'id',
      'name',
      'product_code',
      'hsn_code',
      'batch_no',
      'agency_name',
      'description',
      'price',
      'distribution_price',
      'salt',
      'tax_id',
     
     [
        'attribute' => 'bill_date',
        'label' => 'Bill Date',
        'value' => (empty($model->bill_date)) ? 'N/A' : date('Y-m-d h:i:s A', strtotime($model->bill_date)),
     ],
     [
        'attribute' => 'expiry_date',
        'label' => 'Expiry Date',
        'value' => (empty($model->expiry_date)) ? 'N/A' : date('Y-m-d h:i:s A', strtotime($model->expiry_date)),
     ],
     [
        'attribute' => 'created_at',
        'label' => 'Created at',
        'value' => (empty($model->created_at)) ? 'N/A' : date('Y-m-d h:i:s A', strtotime($model->created_at)),
     ],
     [
        'attribute' => 'created_at',
        'label' => 'Created at',
        'value' => (empty($model->created_at)) ? 'N/A' : date('Y-m-d h:i:s A', strtotime($model->created_at)),
     ],
     [
        'attribute' => 'created_by_id',
        'label' => 'Created By',
        'value' => !empty($model->createdBy && $model->createdBy->name) ? $model->createdBy->name : 'N/A',
     ],
     'message'
    
    ]
    " />
                </div>
            </div>
        </div>
    </div>

    <x-a-user-action :model="$model" attribute="state_id" :states="$model->getStateOptions()" />


</div>
@endsection