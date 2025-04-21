<form action="{{ route(empty($model->exists) ? 'product.add' : 'product.update', $model->id) }}" method="post"
    id="product-update" enctype="multipart/form-data">

    @csrf
    <div class="row align-items-starts">
        <div class="col-xl-4 col-lg-4 col-md-6 col-12">
            <div class="mb-3 required">
                <label class="pt-2 fw-bold" for="btncheck1"> Name </label>
                <input type="text" class="form-control d-block" name="name"
                    value="{{ old('name', $model->name) }}">
            </div>
            @error('Title')
                <p style="color:red;">{{ $errors->first('name') }}</p>
            @enderror
        </div>

        <div class="col-xl-4 col-lg-4 col-md-6 col-12">
            <div class="mb-3 custom-select-wrapper ">
                <label class="pt-2 fw-bold" for="btncheck1"> MFG <i class="fa fa-plus btn btn-primary btn-sm mb-1"
                        data-bs-toggle="modal" data-bs-target="#customerModal">
                    </i></label>
                <select name="mfg_id" class="validate form-control" id="category_id-1">
                    <option value="">Select MFG</option>
                    @foreach ($model->getCategoryOption(1) as $category)
                        <option value="{{ $category->id }}"
                            {{ old('mfg_id', $model->mfg_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

            </div>
            @error('mfg_id')
                <p style="color:red;">{{ $errors->first('mfg_id') }}</p>
            @enderror
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 col-12">
            <div class="mb-3 ">
                <label class="pt-2 fw-bold" for="btncheck1"> Price </label>
                <input type="text" class="form-control d-block" name="price"
                    value="{{ old('price', $model->price) }}">
            </div>
            @error('price')
                <p style="color:red;">{{ $errors->first('price') }}</p>
            @enderror
        </div>



        <div class="col-xl-4 col-lg-4 col-md-6 col-12">
            <div class="mb-3 ">
                <label class="pt-2 fw-bold" for="btncheck1"> MRP Price </label>
                <input type="text" class="form-control d-block" name="mrp_price"
                    value="{{ old('mrp_price', $model->mrp_price) }}">
            </div>
            @error('mrp_price')
                <p style="color:red;">{{ $errors->first('mrp_price') }}</p>
            @enderror
        </div>



        <div class="col-xl-4 col-lg-4 col-md-6 col-12">
            <div class="mb-3 ">
                <label class="pt-2 fw-bold" for="btncheck1"> Quantity </label>
                <input type="text" class="form-control d-block" name="quantity"
                    value="{{ old('quantity', $model->quantity) }}">
            </div>
            @error('quantity')
                <p style="color:red;">{{ $errors->first('quantity') }}</p>
            @enderror
        </div>


        <input type="hidden" name="id" id="id" value="{{ $model->id }}" />



        <div class="col-xl-4 col-lg-4 col-md-6 col-12">
            <div class="mb-3 ">
                <label class="pt-2 fw-bold" for="btncheck1"> HSN Code </label>
                <input type="text" class="form-control d-block" name="hsn_code"
                    value="{{ old('hsn_code', $model->hsn_code) }}">
            </div>
            @error('hsn_code')
                <p style="color:red;">{{ $errors->first('hsn_code') }}</p>
            @enderror
        </div>

        <div class="col-xl-4 col-lg-4 col-md-6 col-12">
            <div class="mb-3 ">
                <label class="pt-2 fw-bold" for="btncheck1"> Expiry Date </label>
                <input type="month" class="form-control d-block" name="expiry_date"
                    value="{{ old('expiry_date', \Carbon\Carbon::parse($model->expiry_date)->format('Y-m')) }}">


            </div>
            @error('expiry_date')
                <p style="color:red;">{{ $errors->first('expiry_date') }}</p>
            @enderror
        </div>


        <div class="col-xl-4 col-lg-4 col-md-6 col-12">
            <div class="mb-3 ">
                <label class="pt-2 fw-bold" for="btncheck1"> Salt </label>
                <input type="text" class="form-control d-block" name="salt"
                    value="{{ old('salt', $model->salt) }}">
            </div>
            @error('salt')
                <p style="color:red;">{{ $errors->first('salt') }}</p>
            @enderror
        </div>

        <div class="col-xl-4 col-lg-4 col-md-6 col-12">
            <div class="mb-3 custom-select-wrapper">
                <label class="pt-2 fw-bold" for="btncheck1"> Agency Name <i
                        class="fa fa-plus btn btn-primary btn-sm mb-1"data-bs-toggle="modal"
                        data-bs-target="#agencyName"></i></label>
                <select name="agency_id" class="validate form-control" id="category_id-0">
                    <option value="">Select MFG</option>
                    @foreach ($model->getCategoryOption(0) as $category)
                        <option value="{{ $category->id }}"
                            {{ $category->id == $model->agency_id ? 'selected' : '' }}>{{ $category->name }}
                        </option>
                    @endforeach

                </select>
            </div>
            @error('agency_id')
                <p style="color:red;">{{ $errors->first('agency_id') }}</p>
            @enderror
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 col-12">
            <div class="mb-3 ">
                <label class="pt-2 fw-bold" for="btncheck1"> Batch Number </label>
                <input type="text" class="form-control d-block" name="batch_no"
                    value="{{ old('batch_no', $model->batch_no) }}">
            </div>
            @error('batch_no')
                <p style="color:red;">{{ $errors->first('batch_no') }}</p>
            @enderror
        </div>

        <div class="col-xl-4 col-lg-4 col-md-6 col-12">
            <div class="mb-3 ">
                <label class="pt-2 fw-bold" for="btncheck1">PKG.</label>
                <textarea rows="1" name="pkg" id="pkg" class="validate form-control">{{ $model->pkg }} </textarea>
            </div>
            @error('pkg')
                <p style="color:red;">{{ $errors->first('pkg') }}</p>
            @enderror
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 col-12">
            <div class="mb-3 ">
                <label class="pt-2 fw-bold" for="btncheck1"> Bill Date </label>
                <input type="month" class="form-control d-block" name="bill_date"
                    value="{{ old('bill_date', \Carbon\Carbon::parse($model->bill_date)->format('Y-m')) }}">

            </div>
            @error('bill_date')
                <p style="color:red;">{{ $errors->first('bill_date') }}</p>
            @enderror
        </div>

        <div class="col-xl-4 col-lg-4 col-md-6 col-4">
            <div class="mb-6">
                <label for="message">Bill Images</label>
                <div class="input-group">
                    <input type="file" class="form-control ticket_images" name="images[]" multiple
                        onchange="previewImages(event)">
                </div>
            </div>
            @error('image')
                <p style="color:red;">{{ $errors->first('image') }}</p>
            @enderror
        </div>
        <div class="preview-images"></div>
        <div class="col-lg-12">
            <div class="d-flex align-items-center justify-content-end">
                <div class="downoad-btns text-end my-4">
                    <button class="btn btn-primary text-white ms-2">
                        @empty($model->exists)
                            {{ __('Add') }}
                        @else
                            {{ __('Update') }}
                        @endempty
                    </button>

                </div>
            </div>
        </div>


    </div>
</form>


<div class="modal fade" id="customerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1">Add MFG</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/add-mfg" method="POST" class="ajax-form" id="mfgForm"
                data-success-callback="formSuccessCallback">
                <input type="hidden" name="type_id" value="1" class="form-control" />

                <div class="modal-body">

                    <div class="row g-2 mt-2">
                        <div class="col mb-0">
                            <label for="mfg" class="form-label">MFG</label>
                            <input type="text" id="mfg" name="product_type" class="form-control"
                                placeholder="mfg.." />
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" id="add-customer" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>



<div class="modal fade" id="agencyName" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1">Agency Name</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/add-mfg" method="POST" class="ajax-form" id="formAgencyName"
                data-success-callback="formSuccessCallback">

                <div class="modal-body">
                    <input type="hidden" name="type_id" value="0" class="form-control" />
                    <div class="row g-2 mt-2">
                        <div class="col mb-0">
                            <label for="mfg" class="form-label">Agency</label>
                            <input type="text" id="mfg" name="product_type" class="form-control"
                                placeholder="agency name.." />
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" id="agencyNameButton" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $(document).on('submit', '#mfgForm', function(e) {
            e.preventDefault();
            let formData = $(this).serialize();
            ajaxRequest(formData);
            $('#customerModal').modal('hide');
        });

        $(document).on('submit', '#formAgencyName', function(e) {
            e.preventDefault();
            let formData = $(this).serialize();
            ajaxRequest(formData);
            $('#agencyName').modal('hide');
        });

    });

    function ajaxRequest(data) {
        $.ajax({
            url: "{{ url('product/add-mfg') }}", // Your endpoint URL
            method: 'POST',
            data: data,
            success: function(response) {
                // Call your success callback
                formSuccessCallback(response);
            },
            error: function(err) {
                console.log(err);
            }
        });

    }

    function formSuccessCallback(response) {

        console.log('#category_id' + response.type_id);
        // Assuming response returns updated category list
        let categorySelect = $('#category_id-' + response.type_id); // Adjust selector as needed
        categorySelect.empty(); // Clear old options

        // Append new options
        $.each(response.categories, function(index, category) {
            categorySelect.append(
                $('<option>', {
                    value: category.id,
                    text: category.name
                })
            );
        });
    }
</script>
