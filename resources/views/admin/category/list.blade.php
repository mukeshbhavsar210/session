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
            <div class="col-md-2 col-12 float-end">
                <a href="javascript:0" class="btn btn-primary float-right" data-bs-toggle="modal" data-bs-target="#addCategoryModal">Add Category</a>
            </div>
        </div>        
                
        <div class="accordion mt-2" id="categoryAccordion">
            @if ($categories->isNotEmpty())
                @foreach ($categories as $value)
                    <div class="accordion-item">                        
                        <h2 class="accordion-header" id="heading_{{ $value->id }}">                            
                            <button class="accordion-button p-xl-1 {{ !$loop->first ? 'collapsed' : '' }}" 
                                    type="button" 
                                    data-bs-toggle="collapse" 
                                    data-bs-target="#collapse_{{ $value->id }}" 
                                    aria-expanded="{{ $loop->first ? 'true' : 'false' }}" 
                                    aria-controls="collapse_{{ $value->id }}">

                                    @if($value->image)
                                        <img src="{{ asset('uploads/category/'.$value->image) }}" style="height: 50px; margin-right:10px;" class="shadow-sm rounded-circle" />
                                        @else
                                        <img src="{{ asset('admin-assets/img/default-150x150.png') }}" alt="" height="90" class="me-3 align-self-center rounded" />
                                    @endif

                                    {{ $value->name }}
                                    <span class="badge rounded text-blue bg-blue-subtle ms-2">{{ $value->menus_count }}</span>  
                                    
                                    <a href="{{ route('categories.delete', $value->id) }}" class="delete-icon float-end">
                                        <span class="sprites"></span>
                                    </a>
                            </button>                            
                        </h2>
                        
                        <div id="collapse_{{ $value->id }}" 
                            class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" 
                            aria-labelledby="heading_{{ $value->id }}" 
                            data-bs-parent="#categoryAccordion">

                            <div class="accordion-body">
                                <div class="chip-wrapper">                                    
                                    <a href="javascript:0" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMenuModal_{{ $value->id }}">Create Item</a>

                                    <div class="modal fade" id="addMenuModal_{{ $value->id }}" tabindex="-1" aria-labelledby="addMenuModalLabel_{{ $value->id }}" aria-hidden="true" style="display: none;">
                                        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Add Menu</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>                
                                                </div>

                                                <form action="{{ route('menu.store') }}" method="post">
                                                    <div class="modal-body">
                                                        @csrf
                                                        <div class="form-group">
                                                            <label>Category</label>
                                                            <input type="text" readonly value="{{ $value->name }}"  class="form-control">
                                                        </div>
                                                        <input type="hidden" name="category" value="{{ $value->id }}">
                                                        
                                                        <div class="form-group">
                                                            <label>Name</label>
                                                            <input type="text" name="name" class="form-control slug-source" data-target="#slug">
                                                            <input type="hidden" readonly name="slug" id="slug" class="form-control">
                                                            @error('name')
                                                                <p>Please add Menu item</p>
                                                            @enderror
                                                        </div>
                                                        
                                                        <button type="submit" class="btn btn-primary mt-2">Create</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    @if ($value->menus->count())                                    
                                        @foreach ($value->menus as $menu)
                                            <div class="color-chip">
                                                <span class="color-title">{{ $menu->name }}</span>                                                
                                                <a href="{{ route('menu.delete', $menu->id) }}" class="delete-icon float-end">
                                                    <span class="sprites"></span>
                                                </a>
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


                    
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered" role="document">
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
                    <label for="image">Picture</label>
                    <input type="file" class="form-control" name="image" />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Create</button>
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
