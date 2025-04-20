@extends('layouts.master')
@section('title', 'User View')
@section('content')

<style type="text/css">
    .grid-image {
        width: 183px;
        height: 119px;
    }
</style>
<?php

use App\Models\User;
?>
<x-a-breadcrumb :columns="[
        [
            'url' => '/',
            'label' => 'Home',
        ],
        [
            'url' => 'user',
            'label' => 'Users',
        ],
        $model->name,
    ]" />

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <div class="card-body">
                    <h5>{{ !empty($model->name) ? (strlen($model->name) > 100 ? substr($model->name, 0, 100) . '...' : $model->name) : 'N/A' }}
                        <span class="{{ $model->getStateBadgeOption() }}">{{ $model->getState() }}</span>
                    </h5>
                    <div class="row">

                        <div class="col-md-3 col-lg-2">
                            <div class="admin-blog-image mt-5">

                                <img src="{{ asset($model->profile_image ? '/uploads/' . $model->profile_image : '/assets/img/avatars/1.png') }}" alt="Profile" class="grid-image">

                            </div>
                        </div>
                        <div class="col-md-9 col-lg-10">



                            <x-a-detail-view :model="$model" :type="'double'" :column="[
                                    'id',
                                    'email',
                                    'name',
                                    [
                                        'attribute' => 'role_id',
                                        'label' => 'Role',
                                        'value' => $model->getRole(),
                                        'visible' => true,
                                    ],
                                    [
                                        'attribute' => 'email_verified',
                                        'label' => 'Email Verified',
                                        'value' => $model->getEmail(),
                                        'visible' => true,
                                    ],
                                    [
                                        'attribute' => 'created_at',
                                        'label' => 'Created at',
                                        'value' => empty($model->created_at)
                                            ? 'N/A'
                                            : date('Y-m-d h:i:s A', strtotime($model->created_at)),
                                    ],
                                    [
                                        'attribute' => 'updated_at',
                                        'label' => 'Updated at',
                                        'value' => empty($model->updated_at)
                                            ? 'N/A'
                                            : date('Y-m-d h:i:s A', strtotime($model->updated_at)),
                                        'visible' => $model->role_id != User::ROLE_ADMIN && $model->id != Auth::id(),
                                    ],
                                
                                    [
                                        'attribute' => 'created_by_id',
                                        'label' => 'Created By',
                                        'value' => !empty($model->createdBy && $model->createdBy->name)
                                            ? $model->createdBy->name
                                            : 'N/A',
                                        'visible' => true,
                                    ],
                                ]" />
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


    @if ($model->role_id != User::ROLE_ADMIN && $model->id != Auth::id())
    <x-a-user-action :model="$model" attribute="state_id" :states="$model->getStateOptions()" />
    @endif


</div>
@endsection