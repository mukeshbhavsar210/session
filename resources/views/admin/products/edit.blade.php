@extends('admin.layouts.app')

@section('content')

@include('admin.layouts.message')

<div class="card">
    <div class="card-body">
        <div class="row">                
            <div class="col-md-10 col-12">
                <div class="page-title"> 
                    <h4>Edit Product</h4>                    
                </div>
            </div>
            <div class="col-md-2 col-12">
                <a href="{{ route('products.index') }}" class="btn btn-primary float-end">Back</a>
            </div>
        </div>

        <form action="{{ route('products.update',$product->id) }}" method="post" enctype="multipart/form-data" class="mt-2">
            @csrf        
            <div class="row">
                <div class="col-md-6 col-6">
                    <div class="produtName form-group">
                        <label for="name">Item Name</label>
                        <div>
                            <input type="text" name="name" id="name" class="form-control slug-source" data-target="#slug" placeholder="Name" value="{{ $product->name }}">
                            <input type="hidden" readonly name="slug" id="slug" class="form-control" placeholder="slug">                                
                            <p class="error"></p>
                        </div>

                            <div class="vegContainer">
                                <label for="name">Veg/Non-veg?</label><br />
                                <div class="btn-group" id="options">
                                    <label class="btn btn-default active">
                                        <input type="radio" name="veg_nonveg" class="btn-check" value="Veg" checked>
                                        <div class="innerView">
                                            <img src="{{ asset('admin-assets/images/veg.svg') }}" alt="">
                                        </div>
                                    </label>

                                    <label class="btn btn-default">
                                        <input type="radio" name="veg_nonveg" class="btn-check" value="Non-veg">
                                        <div class="innerView">
                                            <img src="{{ asset('admin-assets/images/non-veg.svg') }}" alt="">
                                        </div>
                                    </label>

                                    <label class="btn btn-default">
                                        <input type="radio" name="veg_nonveg" class="btn-check" value="Egg">
                                        <div class="innerView">
                                            <img src="{{ asset('admin-assets/images/egg.svg') }}" alt="">
                                        </div>
                                    </label>
                                </div>
                            </div>

                        <div class="vegContainer">
                            <div class="btn-group" name="veg_nonveg" id="options" data-toggle="buttons">
                                <label class="btn btn-default active">
                                <input type="radio" checked name="veg_nonveg" id="option1" class="btn-check" value="Veg">
                                    <div class="innerView">
                                        <img src="{{ asset('admin-assets/images/veg.svg') }}" alt="" >
                                    </div>                                          
                                </label>

                                <label class="btn btn-default" >
                                <input type="radio" name="veg_nonveg" id="option2" class="btn-check" value="Non-veg" >
                                    <div class="innerView">
                                        <img src="{{ asset('admin-assets/img/non-veg.svg') }}" alt="" >
                                    </div>
                                </label>
                                
                                <label class="btn btn-default" >
                                <input type="radio" name="veg_nonveg" id="option3" class="btn-check" value="Egg" >
                                    <div class="innerView">
                                        <img src="{{ asset('admin-assets/images/egg.svg') }}" alt="" >
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>                    
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" cols="10" rows="4" class="form-control" placeholder="Description">{{ $product->description }}</textarea>
                    </div>
                </div>

                <div class="col-md-6 col-6">
                    <div class="form-group">                                                                
                        <label for="image">Picture</label>
                        <input type="file" class="form-control" name="image" />
                    </div>    
                    
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="category">Choose Menu</label>
                                <select name="category" id="category" class="form-select">
                                <option value="">Select a category</option>
                                    @if ($categories->isNotEmpty())
                                        @foreach ($categories as $value)
                                            <option {{ ($product->category_id == $value->id) ? 'selected' : '' }} value="{{ $value->id }}">{{ $value->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <p class="error"></p>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="category">Item category</label>
                                <select name="menu" id="sub_category" class="form-select">
                                    <option value="">Select a Sub category</option>
                                    @if ($subCategories->isNotEmpty())
                                        @foreach ($subCategories as $subCategory)
                                            <option {{ ($product->menu_id == $subCategory->id) ? 'selected' : '' }} value="{{ $subCategory->id }}">{{ $subCategory->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>                            
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="price">Price</label>
                                <input type="text" name="price" id="price" class="form-control" placeholder="Price" value="{{ $product->price }}">
                                <p class="error"></p>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="compare_price">Offer Price</label>
                                <input type="text" name="compare_price" id="compare_price" class="form-control" placeholder="Office Price" value="{{ $product->compare_price }}">
                                <p class="error"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Update Product</button>
            <a href="{{ route('products.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>            
        </form>
    </div>
</div>
@endsection

@section('customJs')
<script>
    $("#category").change(function(){
        var category_id = $(this).val();
        $.ajax({
            url: '{{ route("product-subcategories.index") }}',
            type: 'get',
            data: {category_id:category_id},
            dataType: 'json',
            success: function(response) {
                $("#sub_category").find("option").not(":first").remove();
                $.each(response["subCategories"],function(key,item){
                    $("#sub_category").append(`<option value='${item.id}' >${item.name}</option>`)
                })
            },
            error: function(){
                console.log("Something went wrong")
            }
        });
    })    

    $(document).ready(function () {
        $('input[name="veg_nonveg"]').on('change', function () {
            // Remove active class from all labels
            $('#options label').removeClass('active');

            // Add active class to selected radio's parent label
            $(this).closest('label').addClass('active');

        });
    });
</script>
@endsection