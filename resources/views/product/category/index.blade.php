@extends('layouts.master')
@section('title', 'wallet Index')

@section('content')
    <style>
        .category-type {
            margin-left: 10px
        }
    </style>

    <!-- preg_replace('/([a-z])([A-Z])/', '$1 $2', class_basename($model)) -->

    <x-a-breadcrumb :columns="[
        [
            'url' => '/',
            'label' => 'Home',
        ],
    ]" />



    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12 mb-4 order-0">

                <div class="card">
                    <div class="card-header">
                        <h3>{{ __('Add') }}</h3>
                    </div>
                    <div class="card-body">
                        <form id="blog-category-form" class="row needs-validation justify-content-center"
                            Action="{{ route('productCategory.add') }}" method="post" novalidate>
                            @csrf
                            <div class="col-md-8">
                                <div class="d-md-flex align-items-start">

                                    <div class="flex-grow-1 ">
                                        <div class="mb-3 field-category-title required custom-select-wrapper">
                                            <label class="form-label" for="category-title">Category Type</label>
                                            <select name="type_id" class="validate form-control" id="type_id">
                                                @foreach ($model->getTypeOptions() as $key => $role)
                                                    <option value="{{ $key }}"
                                                        {{ old('type_id', $model->type_id) == $key ? 'selected' : '' }}>
                                                        {{ $role }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="flex-grow-1 category-type">
                                        <div class="mb-3 field-category-title required">
                                            <label class="form-label" for="category-title">Name</label>
                                            <input type="text" id="category-title" class="form-control"
                                                value="{{ old('name') }}" id="validationCustom01" name="name"
                                                maxlength="150">
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
                            <x-a-grid-view :id="'product_category_table'" :model="$model" :url="'product/category/get-list/'" :columns="['id', 'name', 'type', 'status', 'created_at', 'created_by', 'action']" />
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
