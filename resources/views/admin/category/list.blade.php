@extends('admin.layouts.app')

@section('content')

@include('admin.layouts.message')

<div class="card">
    <div class="card-body">
        <div class="row">                
            <div class="col-md-10 col-12">
                <div class="page-title"> 
                    <h4>Category</h4>        
                    <span class="counts">{{ $totalMenu }}</span>                    
                </div>
            </div>
            <div class="col-md-2 col-12">
                <a href="javascript:0" class="btn btn-primary pull-right" data-bs-toggle="modal" data-bs-target="#addCategoryModal">Add Category</a>
            </div>
        </div>        
                
        <div class="accordion mt-2" id="categoryAccordion">
            @if ($categories->isNotEmpty())
                @foreach ($categories as $value)
                    <div class="accordion-item">                        
                        <div class="accordion-header" id="heading_{{ $value->id }}">
                            
                                <button class="accordion-button p-xl-2 {{ !$loop->first ? 'collapsed' : '' }}" 
                                        type="button" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#collapse_{{ $value->id }}" 
                                        aria-expanded="{{ $loop->first ? 'true' : 'false' }}" 
                                        aria-controls="collapse_{{ $value->id }}">

                                        @if($value->image)
                                            <img src="{{ asset('uploads/category/'.$value->image) }}" style="height: 50px; width:50px; margin-right:10px;" class="shadow-sm rounded-circle" />
                                        @else
                                            <img src="{{ asset('admin-assets/images/default-150x150.png') }}" alt="" height="90" class="me-3 align-self-center rounded" />
                                        @endif

                                        <h5 class="mb-0">{{ $value->name }}</h5>
                                        <span class="badge rounded text-blue bg-blue-subtle ms-2">{{ $value->menus_count }}</span>  
                                </button>
                        </div>
                        
                        <div id="collapse_{{ $value->id }}" 
                            class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" 
                            aria-labelledby="heading_{{ $value->id }}" 
                            data-bs-parent="#categoryAccordion">

                            <div class="accordion-body">                                
                                <a href="javascript:0" class="btn btn-primary float-right" data-bs-toggle="modal" data-bs-target="#addMenuModal">Add Menu</a>
                                <a href="{{ route('category.delete', $value->id) }}" class="btn btn-outline-danger">Delete</a> 

                                <div class="row mt-2">
                                    @if ($value->menus->count())                                    
                                        @foreach ($value->menus as $menu)
                                        <div class="col-md-2 col-6">
                                            <div class="card border-show">
                                                @if($menu->image)
                                                    <img src="{{ asset('uploads/menu/'.$menu->image) }}" class="card-img-top img-fluid bg-light-alt" />
                                                @else
                                                    <img src="{{ asset('admin-assets/images/default-150x150.png') }}" alt="" class="card-img-top img-fluid bg-light-alt" />
                                                @endif
                                                
                                                <div class="card-header">
                                                    <h4 class="card-title">{{ $menu->name }}</h4>
                                                    <a href="{{ route('menu.delete', $menu->id) }}" class="btn btn-outline-danger btn-sm mt-2">Delete</a>
                                                </div>                                                
                                            </div>
                                        </div>
                                        @endforeach                                                                      
                                    @endif                                    
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

<div class="modal fade drawer right-align" id="addMenuModal" tabindex="-1" aria-labelledby="addMenuModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Menu Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('menu.store') }}" method="post"  enctype="multipart/form-data">                                        
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="category" value="{{ $value->id }}">                    
                                
                    <div class="form-group">
                        <label>Item Name</label>
                        <input type="text" name="name" id="name" class="form-control slug-source" data-target="#slug" placeholder="Enter menu name">
                        <input type="hidden" name="slug" id="slug">                        
                    </div>

                    <div class="form-group">
                        <label for="image">Item Picture</label>
                        <input type="file" class="form-control" name="image" />
                    </div>

                    @error('name')
                        <small class="text-danger">Please add Menu item</small>
                    @enderror

                    <button type="submit" class="btn btn-primary mt-2">Create Item</button>
                </div>
            </form>        
        </div>
    </div>
</div>
                    
<div class="modal fade drawer right-align" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add Category</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <form action="{{ route('categories.store') }}" method="post" enctype="multipart/form-data" >
            @csrf
                <div class="modal-body">        
                    <div class="form-group">
                        <label for="name">Category Name</label>
                        <input type="text" name="name" id="name" class="form-control slug-source" placeholder="Category Name" data-target="#slug">
                        <input type="hidden" readonly name="slug" id="slug" class="form-control" >
                        <p></p>
                    </div> 

                    <div class="form-group">
                        <label for="image">Item Picture</label>
                        <input type="file" class="form-control" name="image" />
                    </div>
                        
                    <button type="submit" class="btn btn-primary mt-2">Create Category</button>            
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('customJs')
<script>    
    $(function(e){
        $("#select_all_ids").click(function(e){
            $('.checkbox_ids').prop('checked',$(this).prop('checked'));
        });

        $('#deleteAllSelectedRecord').click(function(e){
            e.preventDefault();
            var all_ids = [];
            $('input:checkbox[name=ids]:checked').each(function(){
                all_ids.push($(this).val());
            });

            $.ajax({
                url: "{{ route('menuall.delete') }}",
                type: 'delete',
                data:{
                    ids:all_ids,                    
                    _token:'{{ csrf_token() }}'
                },
                success:function(response){
                    $.each(all_ids,function(key,val){
                        $('#menu_ids_'+val).remove();
                    });
                }
            });
        });
    });

    $('#item_name').change(function(){
        element = $(this);
        $("button[type=submit]").prop('disabled', true);
        $.ajax({
            url: '{{ route("getSlug") }}',
            type: 'get',
            data: {title: element.val()},
            dataType: 'json',
            success: function(response){
                $("button[type=submit]").prop('disabled', false);
                if(response["status"] == true){
                    $("#item_slug").val(response["slug"]);
                }
            }
        });
    })   

    //DELETE
    function deleteCategory(id){
        var url = '{{ route("categories.delete","ID") }}'
        var newUrl = url.replace("ID",id)

        if(confirm("Are you sure you want to delete?")){
            $.ajax({
                url: newUrl,
                type: 'delete',
                data: {},
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response){
                    window.location.href="{{ route('categories.index') }}"
                }
            });
        }
    }
    </script>
@endsection
