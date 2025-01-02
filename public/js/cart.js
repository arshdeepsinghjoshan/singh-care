
$(document).ready(function () {
    console.log('i am working');
    
    // Set up CSRF token for AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('.changeQuantity').click(function () {
        increment(this);
    });
    // Event listener for state change
    function increment(e) {
        var product = JSON.parse(e.getAttribute("data-product"))
        var product_id = product?.product?.id || 0;
        var type_id = e.getAttribute("data-type")
        setQuantity(product_id, type_id);
    }

    async function setQuantity(product_id, type_id) {
        $.ajax({
            url: "/cart/change-quantity",
            type: 'POST',
            data: {
                product_id: product_id,
                type_id: type_id,
            },
            success: function (res) {

                if (res.status == 200) {
                    // getCartItems(id, total_field, nextInputElement);
                    // UpdateTotalPrice(total_field, total_price, nextInputElement, total_qty);
                    handleResponse(res);
                }
                if (res.status == 422) {
                    handleResponse(res);
                }
                $('#userd_table').DataTable().ajax.reload(null, false)
            }
        });


    }

    async function updateState(unique_id, model_id, model_type) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: $('#app').data('state-change-url'),
                type: 'POST',
                data: {
                    model_type: model_type,
                    model_id: model_id,
                    attribute: 'state_id', // Replace with your actual attribute
                    workflow: unique_id, // Assuming unique_id is the workflow state
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    resolve(response);
                },
                error: function (xhr, status, error) {
                    reject(xhr.responseText);
                }
            });
        });
    }

    function handleResponse(response) {
        var toastG = document.getElementById('toastG');
        var toastBody = toastG.querySelector('.toast-body');

        if (response.status === 200) {
            toastG.classList.remove('bg-danger'); // Remove error class if previously set
            toastG.classList.add('bg-success');   // Set success class

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

        toastG.classList.remove('bg-success');  // Remove success class if previously set
        toastG.classList.add('bg-danger');       // Set error class

        // Update toast message
        toastBody.innerText = error;

        // Show toast using Bootstrap's method
        var bsToast = new bootstrap.Toast(toastG);
        bsToast.show();
    }
});
