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
                        <x-a-grid-view :id="'user_table'" :model="$model" :url="'product/get-list'" :columns="[
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

                        <x-a-grid-view :id="'userd_table'" :model="$model" :url="'cart/get-list'" :columns="[
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
<div class="card mb-4">
                <h5 class="card-header">Bootstrap Toasts Example With Placement</h5>
                <div class="card-body">
                  <div class="row gx-3 gy-2 align-items-center">
                    <div class="col-md-3">
                      <label class="form-label" for="selectTypeOpt">Type</label>
                      <select id="selectTypeOpt" class="form-select color-dropdown">
                        <option value="bg-primary" selected>Primary</option>
                        <option value="bg-secondary">Secondary</option>
                        <option value="bg-success">Success</option>
                        <option value="bg-danger">Danger</option>
                        <option value="bg-warning">Warning</option>
                        <option value="bg-info">Info</option>
                        <option value="bg-dark">Dark</option>
                      </select>
                    </div>
                    <div class="col-md-3">
                      <label class="form-label" for="selectPlacement">Placement</label>
                      <select class="form-select placement-dropdown" id="selectPlacement">
                        <option value="top-0 start-0">Top left</option>
                        <option value="top-0 start-50 translate-middle-x">Top center</option>
                        <option value="top-0 end-0">Top right</option>
                        <option value="top-50 start-0 translate-middle-y">Middle left</option>
                        <option value="top-50 start-50 translate-middle">Middle center</option>
                        <option value="top-50 end-0 translate-middle-y">Middle right</option>
                        <option value="bottom-0 start-0">Bottom left</option>
                        <option value="bottom-0 start-50 translate-middle-x">Bottom center</option>
                        <option value="bottom-0 end-0">Bottom right</option>
                      </select>
                    </div>
                    <div class="col-md-3">
                      <label class="form-label" for="showToastPlacement">&nbsp;</label>
                      <button id="showToastPlacement" class="btn btn-primary d-block">Show Toast</button>
                    </div>
                  </div>
                </div>
              </div>

<script>
    function increment(e) {
        var product = JSON.parse(e.getAttribute("data-product"))
        var product_id = product?.product?.id || 0;
        var type_id = 1;
        setQuantity(product_id, type_id);
    }

    function setQuantity(product_id, type_id) {
        $.ajax({
            url: "{{route('cart.change_quantity')}}",
            type: 'POST',
            data: {
                product_id: product_id,
                type_id: type_id,
            },
            success: function(res) {
                if (res.status == 200) {

                    getCartItems(id, total_field, nextInputElement);
                    UpdateTotalPrice(total_field, total_price, nextInputElement, total_qty);
                }
                if (res.status == 422) {
                    toastr.error(res.message);
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: res.message,
                    });
                }
            }
        });
    }

    function UpdateTotalPrice(total_field, total_price, nextInputElement, total_qty) {

        getCartDetails()
    }

    function getCartDetails() {
        $.ajax({
            url: '/user-cart-details',
            type: 'get',
            success: function(res) {
                console.log(res);
                if (res.status == 200) {
                    $("#subtotal").text(`RS ${res.total_price}`)
                    $("#total").text(`RS ${res.total_price}`);
                    product_ids = res.product_ids;
                    amount = res.total_price * 100;
                    // UpdateTotalPrice(total_field, total_price, nextInputElement, total_qty);
                }
                if (res.status == 201) {
                    showToastr(res.message, "error");
                }
            }
        })
    }


    function getCartItems(product_id, total_field, nextInputElement) {
        $.ajax({
            url: '/get-cart-items',
            type: 'get',
            data: {
                product_id: product_id
            },

            success: function(res) {

                if (res.status == 200) {
                    total_field.innerText = "Rs " + res.cartModel.total_price;
                    nextInputElement.value = res.cartModel.quantity;
                }
                if (res.status == 201) {
                    showToastr(res.message, "error");
                }
            }
        })
    }
</script>
@endsection