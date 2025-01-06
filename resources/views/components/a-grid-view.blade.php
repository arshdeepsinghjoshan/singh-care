<!-- <link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.dataTables.css" /> -->
<table id="{{ $id }}" class="table">
    <thead>
        <tr>
            @foreach ($columns as $column)
            <th>
                @if (method_exists($model, 'attributeLabels'))
                {{ $model->attributeLabels($column) }}
                @else
                @if (is_array($column))
                @if (isset($column['label']))
                {{ $column['label'] }}
                @else
                {{ ucwords(str_replace('_', ' ', $column['attribute'])) }}
                @endif
                @else
                {{ ucwords(str_replace('_', ' ', $column)) }}
                @endif
                @endif
            </th>
            @endforeach
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>


<script>
    (function($) {
        'use strict';
        // Ensure $id is valid before using it in a jQuery selector
        var tableId = '{{ $id }}';
        var searching = '{{ $searching }}';
        var paging = '{{ $paging }}';
        var info = '{{ $info }}';
        if (tableId) { // Added check to ensure tableId is not empty
            var table = $('#' + tableId).DataTable({
                order: [],
                lengthMenu: [
                    [10, 25, 50, 100, 500],
                    [10, 25, 50, 100, 500]
                ],
                processing: true,
                serverSide: true,
                autoWidth: false,
                searching: searching, // Disable search
                info: info, // Disable search
                paging: paging,
                dom: "<'row'<'col-sm-2'l><'col-sm-7 text-center'B><'col-sm-3'f>>tipr",
                language: {
                    processing: '<div class="spinner-border" style="width: 50px; height: 50px;" role="status"><span class="visually-hidden">Loading...</span></div>'
                },
                ajax: {
                    url: "{{ $url }}",
                    type: "GET",
                    data: function(d) {
                        @foreach ($customfilterIds as $customfilterId)
                            var filterId = '{{ $customfilterId }}';
                            if (filterId && $('#' + filterId)
                                .length) { // Check if filterId element exists
                                d[filterId] = $('#' + filterId).val();
                            }
                        @endforeach
                    },
                    error: function(xhr, error, thrown) {
                        console.log('Error details:', xhr.responseText);
                        alert(
                            'An error occurred while loading the data. Please check the console for more details.'
                        );
                    }
                },
                columns: [
                    @foreach ($columns as $column)
                        {
                            @if (is_array($column))
                                data: '{{ $column['attribute'] ?? '' }}',
                                name: '{{ $column['attribute'] ?? '' }}'
                            @else
                                data: '{{ $column }}',
                                name: '{{ $column }}'
                            @endif
                        },
                    @endforeach
                ],
                buttons: [
                    @foreach ($buttons as $button)
                        @if (is_array($button))
                            {
                                @foreach ($button as $key => $value)
                                    '{{ $key }}': '{{ $value }}',
                                @endforeach
                            },
                        @else
                            '{{ $button }}',
                        @endif
                    @endforeach
                ]
            });



            // Ensure $filterButtonId is valid before using it in a jQuery selector
            var filterButtonId = '{{ $filterButtonId }}';
            if (filterButtonId) { // Added check to ensure filterButtonId is not empty
                $('#' + filterButtonId).on('click', function() {
                    table.ajax.reload();
                });
            }
        }


        function tableReload() {
            table.ajax.reload();

        }
        var cart_table_reload = 'cart_table_reload';
        if (cart_table_reload) { // Added check to ensure cart_table_reload is not empty
            $('#' + cart_table_reload).on('click', function() {
                tableReload()
            });
        }

        $(document).on('click', '#placeOrder', function(e) {

            // this.disabled = true;
            e.preventDefault();

            if (tableId == 'cart_checkout') {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "/order/add",
                    type: 'POST',
                    success: function(res) {
                        if (res.status == 200) {
                            $('#placeOrder').prop('disabled', false);
                            handleResponse(res);
                            let table = $('#order_product_table').DataTable(); // Get the DataTable instance
                            table.ajax.reload();
                            let cart_list = $('#cart_list').DataTable(); // Get the DataTable instance
                            cart_list.ajax.reload();
                            tableReload()


                        }
                        if (res.status == 422) {
                            $('#placeOrder').prop('disabled', false);
                            handleResponse(res);
                            tableReload()

                        }

                    }
                });
            }


        });

        if (tableId == 'cart_list' || tableId == 'cart_checkout') {
            $(document).on('click', '.changeQuantity', function(e) {


                if (tableId == 'cart_list') {
                    // Prevent default action (if needed)
                    e.preventDefault();

                    // `this` refers to the button that was clicked
                    var product = JSON.parse(this.getAttribute("data-product"));
                    var product_id = product?.product?.id || 0;
                    var type_id = this.getAttribute("data-type");
                    // Call your function with the appropriate arguments
                    setQuantity(product_id, type_id);
                }
                tableReload()

            });
        }

        $(document).on('click', '.select-product', function(e) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            e.preventDefault();
            const productId = $(this).data('product_id'); // Get the product ID from the data attribute
            const isChecked = $(this).is(':checked')
            this.disabled = true;
            let type_id = ''; // Declared as const
            if (isChecked) {
                type_id = '1'; // Trying to reassign a const variable
            } else {
                type_id = '0'; // Trying to reassign a const variable
            }
            if (tableId == 'order_product_table') {


                $.ajax({
                    url: '/cart/add', // Replace with your API endpoint
                    method: 'POST',
                    data: {
                        product_id: productId,
                        type_id: type_id
                    },
                    success: function(response) {

                        handleResponse(response);
                    },
                    error: function(xhr) {
                        handleResponse(response);

                    }
                });
            }


            tableReload()

        });



        async function setQuantity(product_id, type_id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "/cart/change-quantity",
                type: 'POST',
                data: {
                    product_id: product_id,
                    type_id: type_id,
                },
                success: function(res) {
                    if (res.status == 200) {
                        handleResponse(res);
                    }
                    if (res.status == 422) {
                        handleResponse(res);
                    }
                }
            });


        }

        function handleResponse(response) {
            var toastG = document.getElementById('toastG');
            var toastBody = toastG.querySelector('.toast-body');
            if (response.status === 200) {
                toastG.classList.remove('bg-danger'); // Remove error class if previously set
                toastG.classList.add('bg-success'); // Set success class
                // Update toast message
                toastBody.innerText = response.message;
                // Show toast using Bootstrap's method
                var bsToast = new bootstrap.Toast(toastG);
                bsToast.show();
            } else {
                handleError(response.message);
            }
        }


        function handleError(error) {
            var toastG = document.getElementById('toastG');
            var toastBody = toastG.querySelector('.toast-body');
            toastG.classList.remove('bg-success'); // Remove success class if previously set
            toastG.classList.add('bg-danger'); // Set error class
            // Update toast message
            toastBody.innerText = error;
            // Show toast using Bootstrap's method
            var bsToast = new bootstrap.Toast(toastG);
            bsToast.show();
        }

    })(jQuery);
</script>