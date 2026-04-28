@extends('front.layouts.app')

@section('content')



<div class="menu-content--categories-medium-photo menu-content">
    <section class="menu-products-section menu-products-section--grid">
        <div class="menu-grid">
            @if(!empty($products))
                @foreach($products as $value)
                <div class="menu-product">
                    <div class="menu-product__item">
                        @if(session('cart'))
                            @foreach(session('cart') as $id => $details)
                                <div class="menu-product__item__ordered_qty">
                                    {{ $details['quantity'] }}
                                </div>
                            @endforeach
                        @endif
                        <div class="menu-product__item__toolbar">
                            <div style="flex-grow: 1;"></div>
                            @if(session('wishlist'))
                                @foreach(session('wishlist') as $id => $wishlist)
                                    {{ $wishlist['quantity'] }}                  
                                @endforeach
                            
                                <button class="btn btn--icon btn--icon--xsm btn--icon--active" >
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="#222222" stroke-width="1.2px" x="0px" y="0px" viewBox="-1 -2 14 13" xml:space="preserve"><path d="M11,1c-0.6-0.6-1.5-1-2.3-1C7.8,0,7,0.4,6.3,1L6,1.3L5.7,1C5,0.3,4.2,0,3.3,0S1.6,0.3,1,1C0.3,1.6,0,2.4,0,3.3S0.3,5,1,5.7
                                        l4.8,4.8C5.9,10.6,6,10.6,6,10.6c0.1,0,0.2,0,0.2-0.1L11,5.7c0.6-0.6,1-1.5,1-2.4S11.7,1.6,11,1z"></path></svg>
                                </button> 
                            @else    
                                <a href="{{ route('addwishlist', $value->id ) }}" class="btn btn--icon btn--icon--xsm add-to-wishlist-button">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="#222222" stroke-width="1.2px" x="0px" y="0px" viewBox="-1 -2 14 13" xml:space="preserve"><path d="M11,1c-0.6-0.6-1.5-1-2.3-1C7.8,0,7,0.4,6.3,1L6,1.3L5.7,1C5,0.3,4.2,0,3.3,0S1.6,0.3,1,1C0.3,1.6,0,2.4,0,3.3S0.3,5,1,5.7
                                        l4.8,4.8C5.9,10.6,6,10.6,6,10.6c0.1,0,0.2,0,0.2-0.1L11,5.7c0.6-0.6,1-1.5,1-2.4S11.7,1.6,11,1z"></path></svg>
                                </a>                                
                            @endif
                        </div>
                            <a href="javascript:void(0);" data-toggle="modal" data-target="#basicModal_{{ $value->id }}" >
                                <div class="menu-product__item__img">
                                    @if (!empty($value->image))
                                        <img src="{{ asset('uploads/product/'.$value->image) }}" >
                                    @else
                                        <img src="{{ asset('admin-assets/img/default-150x150.png') }}" alt="" />
                                    @endif                                   
                                </div>
                            </a>
                            <div class="menu-product__item__top-block">
                                <div class="menu-product__item__name text-overflow">{{ $value->name }}</div>
                                <div class="menu-product__item__price no-wrap">
                                    ₹ {{ $value->price }}
                                    {{-- @if ($value->compare_price > 0)
                                        <span class="text-underline"><del>₹ {{ $value->compare_price }}</del></span>
                                    @endif --}}
                                </div>

                                <div class="product-details">
                                    <a href="{{ route('front.addCart', $value->id ) }}" class="btn btn-primary">Add</a>
                                    <span id="adding-cart-{{ $value->id }}" style="display:none;">Added.</span>
                                </div>
                            </div>
                        </div>
                    </div> 
                    
                    <!-- Modal -->
                    <div class="modal fade" id="basicModal_{{ $value->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="menuContainer">
                                    <div class="product-pic">
                                        @if (!empty($value->image))
                                            <img class="card-img-top" src="{{ asset('uploads/product/'.$value->image) }}" >
                                        @else
                                            <img class="card-img-top" src="{{ asset('admin-assets/img/default-150x150.png') }}" alt="" />
                                        @endif
                                    </div>        
                                    <div class="btnControl">
                                        <div class="row p-3">
                                            <div class="col-9">
                                                <a class="link" data-dismiss="modal" aria-label="Close">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"></path></svg>
                                                </a>
                                            </div>
                                            <div class="col-3">
                                                <a href="{{  route('front.wishlist') }}" class="link right">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-suit-heart-fill" viewBox="0 0 16 16"><path d="M4 1c2.21 0 4 1.755 4 3.92C8 2.755 9.79 1 12 1s4 1.755 4 3.92c0 3.263-3.234 4.414-7.608 9.608a.513.513 0 0 1-.784 0C3.234 9.334 0 8.183 0 4.92 0 2.755 1.79 1 4 1z"></path></svg>
                                                </a>
                                            </div>
                                        </div> 
                                    </div>
                                    <div class="product-title">
                                        <div class="row">
                                            <div class="col-9">
                                                <h3>{{ $value->name }}</h3>
                                            </div>
                                            <div class="col-3">
                                                <div class="product-price">
                                                    <span class="price"> ₹{{ $value->price }}</span>
                                                    @if ($value->compare_price > 0)
                                                        <span class="price text-secondary"><del> ₹{{ $value->compare_price }}</del></span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="product-details">
                                        {{-- <a href="javascript:void(0);" data-product-id="{{ $value->id }}" id="add-cart-btn-{{ $value->id }}" class="add-to-cart-button product-add">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2Z"></path></svg>
                                        </a> --}}
                                        {{-- <span id="adding-cart-{{ $value->id }}" style="display:none;">Added.</span> --}}
                                        <p>{{ \Illuminate\Support\Str::limit(strtolower($value->description), 50) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif                       
        </div>
    </section>
</div>




<?php $total = 0 ?>
<div class="mainCartWrapper">
    

    <div class="orderDetails">        
        <div class="orderBottom">
            <div class="nav nav-tabs mb-3" role="tablist">
                <button class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab">Dinein</button>
                <button class="nav-link" data-toggle="tab" href="#tabs-2" role="tab">Takeaway</button>
                <button class="nav-link" data-toggle="tab" href="#tabs-3" role="tab">Delivery</button>
            </div>

            <?php $total = 0 ?>
            @if(session('cart'))
                <div class="basket-page__content__products">
                    @foreach(session('cart') as $id => $details)
                        <form action="dining" method="POST" >
                            @csrf
                            <div class="row">
                                <div class="col-7">
                                    <img style="width: 40px; border-radius:100px; margin-right:5px;" src="{{ asset('uploads/product/'.$details['image']) }}" >  
                                    {{ $details['quantity'] }} x {{ $details['name'] }}</p>
                                    {{-- <input type="hidden" multiple name="name" value="{{ $details['name'] }}">
                                    <input type="hidden" multiple name="qty" value="{{ $details['quantity'] }}"> --}}
                                </div>
                                <div class="col-3 p-0">
                                    <div class="flex">
                                        <?php 
                                            $isEmpty = $details['quantity'];   
                                        ?>
                                        {{-- <input type="hidden" multiple name="name" value="{{ $details['name'] }}"> --}}
                                        @if($isEmpty > 1)
                                            <div class="input-group-btn">
                                                <button class="btn--icon sub" data-id=" ">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                        class="bi bi-dash-lg" viewBox="0 0 16 16">
                                                        <path fill-rule="evenodd"
                                                            d="M2 8a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11A.5.5 0 0 1 2 8Z"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        @else
                                            <div class="input-group-btn">
                                                @if($isEmpty > 0)
                                                    <button class="btn--icon sub" data-id="{{ $id }}" title="sub">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                            class="bi bi-dash-lg" viewBox="0 0 16 16">
                                                            <path fill-rule="evenodd"
                                                                d="M2 8a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11A.5.5 0 0 1 2 8Z"></path>
                                                        </svg>
                                                    </button>
                                                @else
                                                    <button class="btn--icon delete" data-id="{{ $id }}" title="Delete">
                                                        <i class="fa fa-trash"></i>
                                                    </button>                                                    
                                                @endif
                                            </div>
                                        @endif
                                        <div class="input-group-btn">
                                            <button class="btn--icon add" data-id="{{ $id }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                    class="bi bi-plus-lg" viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd"
                                                        d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2Z">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="right">
                                        <p class="my-2">₹ {{ $details['price'] }}</p>
                                        {{-- <input type="hidden" multiple name="price" value="{{ $details['price'] }}"> --}}
                                    </div>
                                </div>
                                <?php $total += $details['price'] * $details['quantity'] ?>
                            </div>
                    @endforeach
                </div>
            @endif
            <input type="hidden" name="total" value="{{ $total }}">
            
            <div class="basket-page__content__total">
                @if(!empty($details))
                    <div>Total:</div>
                    <div style="flex-grow: 1;"></div>
                    ₹{{ $total }}
                @else
                    <div class="col-md-12">
                        <div class="emptyBag">
                            <img src="{{ asset('front-assets/images/empty_bag.png') }}">
                            <h5>Nothing to order</h5>
                        </div>
                    </div>
                @endif
            </div>

            @if(!empty($details))
            <div class="tab-content">
                <div class="tab-pane p-3 active" id="tabs-1" role="tabpanel">
                    @include('front.home.tab_01')
                </div>

                <div class="tab-pane p-3" id="tabs-2" role="tabpanel">
                    @include('front.home.tab_02')
                </div>

                <div class="tab-pane p-3" id="tabs-3" role="tabpanel">
                    @include('front.home.tab_03')
                </div>
            </div>                
            @endif
        </div>
    </div>

    <div class="row" id="cartDetails">
        @include('front.layouts.message')
        <div class="col-11">
            @if(!empty($details))
                Order {{ count((array) session('cart')) }} for ₹ {{ $total }}
            @else
                Order 
            @endif
        </div>
        <div class="col-1">
            <a href="{{ url('clear-cart') }}" class="cart-icon"><i class="fa fa-trash"></i></a>
        </div>
    </div>
</div>

<div class="orderOverlay"></div>

@endsection

@section('customJs')
<script>
    $('#table_number').change(function(){
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
        
    // $("#diningForm").submit(function(event){
    //     event.preventDefault();
    //     var element = $(this);
    //     $("button[type=submit]").prop('disabled', true);
    //     $.ajax({
    //         url: '{{ route("submit.dining") }}',
    //         type: 'post',
    //         data: element.serializeArray(),
    //         dataType: 'json',
    //         success: function(response){
    //             $("button[type=submit]").prop('disabled', false);

    //             if(response["status"] == true){
    //                 window.location.href="{{ route('front.home') }}"
    //             } else {
    //                 var errors = response['errors']
    //             }

    //         }, error: function(jqXHR, exception) {
    //             console.log("Something event wrong");
    //         }
    //     })
    // });

    // $("#takeawayForm").submit(function(event){
    //     event.preventDefault();
    //     var element = $(this);
    //     $("button[type=submit]").prop('disabled', true);
    //     $.ajax({
    //         url: '{{ route("submit.takeaway") }}',
    //         type: 'post',
    //         data: element.serializeArray(),
    //         dataType: 'json',
    //         success: function(response){
    //             $("button[type=submit]").prop('disabled', false);

    //             if(response["status"] == true){
    //                 window.location.href="{{ route('front.home') }}"
    //             } else {
    //                 var errors = response['errors']
    //             }

    //         }, error: function(jqXHR, exception) {
    //             console.log("Something event wrong");
    //         }
    //     })
    // });  

    // $("#deliveryForm").submit(function(event){
    //     event.preventDefault();
    //     var element = $(this);
    //     $("button[type=submit]").prop('disabled', true);
    //     $.ajax({
    //         url: '{{ route("submit.delivery") }}',
    //         type: 'post',
    //         data: element.serializeArray(),
    //         dataType: 'json',
    //         success: function(response){
    //             $("button[type=submit]").prop('disabled', false);

    //             if(response["status"] == true){
    //                 window.location.href="{{ route('front.home') }}"
    //             } else {
    //                 var errors = response['errors']
    //             }

    //         }, error: function(jqXHR, exception) {
    //             console.log("Something event wrong");
    //         }
    //     })
    // });  
        

    //Hide alert 
    $(function() {
        setTimeout(function() { $(".alert").fadeOut(1500); }, 1500)
    })
</script>
@endsection