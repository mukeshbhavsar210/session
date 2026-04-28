@extends('admin.layouts.app')

@section('content')

@include('admin.layouts.message')

<section class="content-header">
    <div class="row">
        <div class="col-sm-9">
            <h1>Category <span class="count">{{ $totalMenu }}</span></h1>
        </div>

        <div class="col-sm-3 ">
            <div class="float-end">
                <button type="button" class="btn btn-primary mr-3" data-toggle="modal" data-target="#addCategory">Add Category</button>
            </div>                
        </div>
    </div>
</section>

<section>
    {{-- Modal --}}
    <div class="modal fade drawer right-align" id="addCategory" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="{{ route('categories.store') }}" method="post" enctype="multipart/form-data" >
                    @csrf
                    <div class="modal-body">        
                        <div class="form-group">
                            <label for="name">Category Name</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Category Name">
                            <p></p>
                        </div> 

                        <input type="hidden" name="slug" id="slug" class="form-control" placeholder="Slug">

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

    <ul class="nav nav-tabs" role="tablist">
        @if ($categories->isNotEmpty())
            @foreach ($categories as $value)
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#tabs_{{ $value->id }}" role="tab">{{ $value->name }} 
                        {{-- {{ $value->name->count('name') }} --}}
                        @if($value->total_products > 0)
                            <span class="count-sub">{{ $value->total_products }}</span>    
                        @endif                                
                    </a>
                </li>
            @endforeach
        @endif
    </ul>
        
    <div class="tab-content">
        @if ($categories->isNotEmpty())
            @foreach ($categories as $value)
                <div class="tab-pane" id="tabs_{{ $value->id }}" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="card">
                                        <div class="card-body">
                                            @if($value->image)
                                                <img style="width: 100%" src="{{ asset('uploads/category/'.$value->image) }}"  />        
                                            @endif
                                        
                                            <form action="{{ route('menu.store') }}" method="post" >
                                                @csrf
                                                <input type="hidden" name="category" value="{{ $value->id }}">
                                                                                
                                                <div class="form-group mt-2">
                                                    <label for="name">Menu Name</label>
                                                    <input type="text" name="name" id="item_name" class="form-control" placeholder="Menu Name">
                                                    @error('name')
                                                        <p>Please add Menu item</p>
                                                    @enderror
                                                </div> 
                                                <input type="hidden" name="slug" id="item_slug" class="form-control" placeholder="Slug">

                                                <button type="submit" class="btn btn-primary">Create</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    @if ($value->menus->count())
                                        <a href="#" class="btn btn-danger" id="deleteAllSelectedRecord">Delete all selected Record</a>
                                        <table class="table border mt-3">
                                            <thead>
                                                <tr>
                                                    <th><input type="checkbox" name="" id="select_all_ids" value="{{ $value->id }}" /></th>                        
                                                    <th>Name</th>
                                                    <th>Action</th>                                
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white"> 
                                                @if ($value->menus->isNotEmpty())
                                                    @foreach ($value->menus as $value)
                                                    <tr id="menu_ids_{{ $value->id }}">
                                                        <td><input type="checkbox" name="ids" class="checkbox_ids" id="" value="{{ $value->id }}" /></td>
                                                        <td>{{ $value->name }}</td>
                                                        <td>
                                                            <a href="{{ route('menu.edit', $value->id ) }}">
                                                                <svg class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                                                </svg>
                                                            </a>
                                                            <a href="{{ route('menu.delete', $value->id) }}" class="text-danger w-4 h-4 mr-1">
                                                                <svg wire:loading.remove.delay="" wire:target="" class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                                    <path	ath fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                                </svg>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                        </table>
                                    @else
                                        <div class="card">
                                            <div class="card-body text-center">
                                                <h3>Create Menu</h3>
                                            </div>
                                        </div>
                                    @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @endif
    </div>    
</section>
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

    $('#name').change(function(){
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
                    $("#slug").val(response["slug"]);
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
