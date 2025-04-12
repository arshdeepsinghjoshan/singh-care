 <style>
     .category-type {
         margin-left: 10px
     }
 </style>
 <form id="blog-category-form" class="row needs-validation justify-content-center"
     action="{{ route('productCategory.update', $model->id) }}" method="post" novalidate>
     @csrf
     <div class="col-md-8">
         <div class="d-md-flex align-items-start">
             <div class="flex-grow-1">
                 <div class="mb-3 field-category-title required">
                     <label class="form-label" for="category-title">Title</label>
                     <input type="text" id="category-title" class="form-control"
                         value="{{ old('name', !empty($model->name) ? $model->name : '') }}" id="validationCustom01"
                         name="name" maxlength="256" aria-required="true">

                     <div class="invalid-feedback">
                         Title cannot be blank.
                     </div>
                 </div>
             </div>

             <div class="flex-grow-1 category-type">
                 <div class="mb-3 field-category-title required">
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
             <div class="mt-3 mt-sm-4 text-center ml-sm-3">
                 <button type="submit" id="blog-category-form-submit" class="btn btn-primary m-1">Save</button>
             </div>
         </div>
     </div>
 </form>
