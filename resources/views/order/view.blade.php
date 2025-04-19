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
            'url' => 'order',
            'label' => 'Orders',
        ],
        $model->order_number,
    ]" />

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12 mb-4 order-0">
                <div class="card">
                    <div class="card-body">
                        <h5>{{ !empty($model->order_number) ? (strlen($model->order_number) > 100 ? substr($model->order_number, 0, 100) . '...' : $model->order_number) : 'N/A' }}
                            <span class="{{ $model->getStateBadgeOption() }}">{{ $model->getState() }}</span>
                        </h5>

                        <x-a-detail-view :model="$model" :column="[
                            'id',
                            'order_number',
                            [
                                'attribute' => 'total_amount',
                                'value' => number_format($model->total_amount, 2),
                                'visible' => true,
                            ],
                            [
                                'attribute' => 'updated_at',
                                'label' => 'Updated at',
                                'value' => empty($model->updated_at)
                                    ? 'N/A'
                                    : date('Y-m-d h:i:s A', strtotime($model->updated_at)),
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
                                'attribute' => 'created_by_id',
                                'label' => 'Created By',
                                'value' => !empty($model->createdBy && $model->createdBy->name)
                                    ? $model->createdBy->name
                                    : 'N/A',
                            ],
                        ]" />
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">

            <div class="col-xl-12">
                <div class="nav-align-top ">
                    <ul class="nav nav-tabs nav-fill" role="tablist">

                        <li class="nav-item">
                            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                                data-bs-target="#navs-justified-wallet" aria-controls="navs-justified-messages"
                                aria-selected="false">
                                <i class="tf-icons bx bx-message-square"></i> Order Items
                            </button>
                        </li>

                        {{-- <li class="nav-item">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                data-bs-target="#navs-justified-installments" aria-controls="navs-justified-messages"
                                aria-selected="false">
                                <i class="tf-icons bx bx-message-square"></i> Installments
                            </button>
                        </li> --}}

                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane show active" id="navs-justified-wallet" role="tabpanel">
                            <div class="table-responsive">



                                <x-a-relation-grid :id="'order_item_table'" :relation="'items'" :model="$model"
                                    :columns="[
                                        'id',
                                        'product_name',
                                        'total_amount',
                                        'quantity',
                                        'status',
                                        'created_at',
                                        'created_by',
                                        'action',
                                    ]" />
                            </div>
                        </div>

                        {{-- <div class="tab-pane fade " id="navs-justified-installments" role="tabpanel">
                            <div class="table-responsive">
                                <x-a-relation-grid :id="'installments_table'" :relation="'installments'" :model="$model"
                                    :columns="['id', 'amount', 'status', 'created_at', 'action']" />
                            </div>
                        </div> --}}




                    </div>
                </div>
            </div>
        </div>





    </div>
@endsection
