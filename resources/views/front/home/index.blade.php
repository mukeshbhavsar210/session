@extends('front.layouts.app')

@section('content')


<section class="menu-products-section menu-products-section--grid">
    <div class="menu-grid">
        @if(!empty($products))
            @foreach($products as $value)
                <div class="menu-product">
                    <div class="menu-product__item">
                        @php
                            $cart = session('cart', []);
                        @endphp

                        @if(isset($cart[$value->id]))                            
                            <div class="menu-product__item__ordered_qty">
                                {{ $cart[$value->id]['quantity'] }}
                            </div>
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
                                <a href="{{ route('addwishlist', $value->id ) }}" class="wishlist-icon">
                                    <span class="sprites"></span>                                    
                                </a>                                
                            @endif
                        </div>
                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#bottomModal_{{ $value->id }}">
                                <div class="menu-product__item__img">
                                    @php
                                        $productImage = $value->product_images->first();
                                    @endphp

                                    @if (!empty($productImage->image))
                                        <img src="{{ asset('uploads/product/small/'.$productImage->image) }}" alt="{{ $value->name }}" >
                                    @else
                                        <img src="{{ asset('admin-assets/img/default-150x150.png') }}" alt="{{ $value->name }}"/>
                                    @endif                                                                       
                                </div>
                            </a>
                            <div class="menu-product__item__top-block">
                                <div class="menu-product__item__name text-overflow ">
                                    <div class="flex-justify">
                                        <p>{{ $value->name }}</p>
                                        <p>₹{{ round($value->price) }}</p>
                                    </div>
                                    {{-- <div class="menu-product__item__price no-wrap">                                        
                                        @if ($value->discounted_price > 0)
                                            <del>₹{{ round($value->discounted_price) }}</del>
                                        @endif
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>                    
                             
                    <div class="modal fade" id="bottomModal_{{ $value->id }}" tabindex="-1">
                        <div class="modal-dialog modal-fullscreen">
                            <div class="modal-content">
                                <div class="menuContainer">                                    
                                    <div class="product-pic">
                                        @php
                                            $productImage = $value->product_images->first();
                                        @endphp

                                        @if (!empty($productImage->image))
                                            <img src="{{ asset('uploads/product/large/'.$productImage->image) }}" alt="{{ $value->name }}" >
                                        @else
                                            <img src="{{ asset('admin-assets/img/default-150x150.png') }}" alt="{{ $value->name }}" />
                                        @endif 
                                    </div>                                    
                                    
                                    <div class="btnControl flex-justify">
                                        <a href="javascript:0" class="back-icon" data-bs-dismiss="modal" aria-label="Close">
                                            <span class="sprites"></span>                                                            
                                        </a>
                                        <a href="{{ route('addwishlist', $value->id ) }}" class="wishlist-icon">
                                            <span class="sprites"></span>                                                             
                                        </a>                                                
                                    </div> 
                                    <div class="product-title flex-justify">
                                        <h2>{{ $value->name }}</h2>
                                        <div class="product-price">
                                            <span class="price"> ₹{{ $value->price }}</span>
                                            @if ($value->discounted_price > 0)
                                                <span class="price text-secondary"><del> ₹{{ $value->discounted_price }}</del></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="product-details">   
                                        @php
                                            $cart = session('cart', []);
                                            $qty = isset($cart[$value->id]) ? $cart[$value->id]['quantity'] : 0;
                                        @endphp

                                        @if($qty > 0)                                            
                                            <div class="add-controls">
                                                <a href="javascript:0" class="sub-qty products-sub" data-id="{{ $value->id }}">
                                                    <span class="sprites"></span>
                                                </a>

                                                <div class="manage-qty">{{ $qty }}</div>                                                    

                                                <a href="javascript:0" class="add-qty products-add" data-id="{{ $value->id }}">
                                                    <span class="sprites"></span>
                                                </a>
                                            </div>
                                        @else
                                            <a href="{{ route('front.addCart', $value->id ) }}" class="add-to-cart-button product-add products-add">
                                                <span class="sprites"></span>                                                    
                                            </a>
                                        @endif

                                        <p>{{ \Illuminate\Support\Str::limit(strtolower($value->description), 50) }}</p>

                                        <div class="mt-3" role="group">
                                            <label class="custom-radio mb-2" for="normal">
                                                <input type="radio"  name="size" id="normal" checked>
                                                <span class="radio-mark"></span>
                                                Normal
                                            </label>
                                            
                                            <label class="custom-radio" for="large">
                                                <input type="radio" name="size" id="large">                                                
                                                <span class="radio-mark"></span>
                                                Large
                                            </label>
                                        </div>
                                    </div>
                                </div>            
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </section>
           
    <div id="bottomSheet" class="bottom-sheet">
        <div class="sheet-content">
            <div class="handle">
                @php
                    $total = 0;
                    if(session()->has('cart')) {
                        foreach(session('cart') as $item) {
                            $total += $item['price'] * $item['quantity'];
                        }
                    }
                @endphp

                @if(session()->has('cart') && count(session('cart')) > 0)
                    Order {{ count(session('cart')) }} for ₹{{ $total }}
                    {{-- <a href="{{ url('clear-cart') }}" class="delete-icon">
                        <span class="sprites"></span>
                    </a> --}}

                    <div class="tab-content tab1 active">
                        <span class="sprites tab1_icon"></span>
                    </div>
                    <div class="tab-content tab2">
                        <span class="sprites tab2_icon"></span>
                    </div>
                    <div class="tab-content tab3">
                        <span class="sprites tab3_icon"></span>
                    </div>
                @else
                    Order
                @endif                                             
            </div>
            <div class="p-4">                        
                @if(session()->has('cart') && count(session('cart')) > 0)
                    <ul class="custom-tabs">
                        <li class="tab-link active" data-tab="tab1">Dine in</li>
                        <li class="tab-link" data-tab="tab2">Takeaway</li>
                        <li class="tab-link" data-tab="tab3">Delivery</li>
                    </ul>
                            
                    <form id="makeOrder" name="makeOrder" method="POST">
                        @csrf
                        <div class="basket-page__content__products">
                            @foreach(session('cart') as $id => $value)                                                            
                                <div class="row mb-1">
                                    <div class="col-7">
                                        <p class="mt-1">{{ $value['quantity'] }} x {{ $value['name'] }}</p>
                                    </div>
                                    <div class="col-3">
                                        <div class="flex">
                                            @if($value['quantity'] > 0)
                                                <div class="qty-box flex align-items-center">
                                                    <a href="javascript:0" class="sub-icon sub-qty" data-id="{{ $id }}">
                                                        <span class="sprites"></span>                                                        
                                                    </a>
                                                    <a href="javascript:0" class="add-icon add-qty" data-id="{{ $id }}">
                                                        <span class="sprites"></span>
                                                    </a>
                                                </div>
                                            @else
                                                <a href="javascript:0" class="add-to-cart add-icon add-qty" data-id="{{ $id }}">
                                                    <span class="sprites"></span>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="right">
                                            <p class="my-2">₹ {{ $value['price'] }}</p>                                                
                                        </div>
                                    </div>
                                    
                                    <?php $total += $value['price'] * $value['quantity'] ?>
                                </div>
                            @endforeach
                        </div>                   
                
                        <div class="basket-page__content__total">
                            <p>Total:</p>
                            <p>₹{{ $total }}</p>                                
                        </div>

                        <div class="basket-page__content__delivery">
                            <div class="tab-content tab3">
                                <p>+ Delivery fee ₹50</p>
                            </div>
                        </div>

                        <input type="hidden" name="order_type" id="order_type" value="dinein" class="form-control">
                        <input type="hidden" name="total_amount" value="{{ $total }}" class="form-control">

                        <div class="basket-page__content__notes mb-2">
                            <textarea name="notes" placeholder="Add note 🙏🏻..." ></textarea>
                        </div>

                        <div class="basket-page__content__delivery-content mb-2">
                            <select class="form-select mb-2" aria-label="Default select example" name="dinein_time">
                                <option selected>When Ready</option>
                                <option value="10">10:00</option>
                                <option value="11">11:00</option>
                                <option value="12">12:00</option>
                            </select>
                        </div>

                        <div class="tab-content tab1 active">
                            <div class="basket-page__content__delivery-content mb-3">
                                <select name="seat_id" id="seat_id" class="form-select mb-3">
                                    <option value="">Table</option>
                                    @foreach ($seats as $value)
                                        {{-- @if(empty($value->area_id))
                                            <option value="{{ $value->id }}">
                                                {{ $value->table_name }}
                                            </option>
                                        @endif --}}
                                        @if($value->area_id == NULL)
                                            <option value="{{ $value->id }}">{{ $value->table_name }}</option>
                                        @elseif($value->area_id == '')
                                            <option value="{{ $value->id }}">{{ $value->table_name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="tab-content tab2">
                            <div class="form-group mb-2">
                                <input type="text" class="form-control" placeholder="Name" name="customer_name">
                            </div>
                            <div class="row">
                                <div class="col-7">
                                    <div class="form-group mb-3">
                                        <input type="email" class="form-control" placeholder="Email" name="customer_email">
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="form-group mb-2">
                                        <input type="phone" class="form-control" placeholder="Phone" name="customer_phone">
                                    </div>
                                </div>
                            </div> 
                        </div>
                        <div class="tab-content tab3">                                
                                <div class="basket-page__content__delivery-content mb-2" >
                                    <textarea class="form-control" id="address" name="address" placeholder="Enter address">address</textarea>
                                </div>

                                <div class="form-group mb-2">
                                    <input type="text" id="customer_name" class="form-control" placeholder="Customer Name" name="customer_name">
                                </div>

                                <div class="row">
                                    <div class="col-7">
                                        <div class="form-group mb-2">
                                            <input type="email" id="customer_email" class="form-control" placeholder="Email" name="customer_email">
                                        </div>
                                    </div>
                                    <div class="col-5">
                                        <div class="form-group mb-2">
                                            <input type="phone" id="customer_phone" class="form-control" placeholder="Phone" name="customer_phone">
                                        </div>
                                    </div>
                                </div>

                                <div style="text-align: center; margin-top: 10px;">
                                <div style="color: var(--ik-error-color); margin: 10px 0px; font-size: 0.9rem;">Fill all required fields</div>
                            </div>

                            <div class="basket-page__content__terms">By clicking Order, you confirm your age is 18+ and you agree to the <a href="https://instalacarte.com/page/privacy-policy" target="_blank">terms</a></div>

                            <div class="basket-order-button-container">
                                <button class="btn btn--brand basket-page__content__order-btn basket-page__content__order-btn--disabled">Order</button>
                            </div> 
                        </div>
                    </form>                
                @else                    
                    <div class="emptyBag">
                        <img src="{{ asset('front-assets/images/empty_bag.png') }}" alt="empty bag" />
                        <p>Nothing to order</p>
                    </div>
                @endif
            </div>
        </div>
        <div class="sheet-overlay"></div>
    </div>                 
@endsection

@section('customJs')
<script>
    $('#seat_id').change(function(){
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
        
    $("#makeOrder").submit(function(event){
         event.preventDefault();
         var element = $(this);
         $("button[type=submit]").prop('disabled', true);
         $.ajax({
             url: '{{ route("submit.dining") }}',
             type: 'post',
             data: element.serializeArray(),
             dataType: 'json',
             success: function(response){
                if (response.status) {
                    $('#successMsg').html(
                        '<div class="alert alert-success">' + response.message + '</div>'
                    );
                }

                 $("button[type=submit]").prop('disabled', false);

                 if(response["status"] == true){
                     window.location.href="{{ route('front.home') }}"
                 } else {
                     var errors = response['errors']
                 }

             }, error: function(jqXHR, exception) {
                 console.log("Something event wrong");
             }
         })
    });

    //Hide alert 
    $(function() {
        setTimeout(function() { $(".alert").fadeOut(1500); }, 1500)
    })

    $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        let type = $(e.target).data('type');
        $('#order_type').val(type);
    });    

    // Increase
    $(document).on('click', '.add-qty', function () {
        let id = $(this).data('id');

        $.ajax({
            url: "/cart/increase",
            type: "POST",
            data: { id: id },
            success: function (res) {
                console.log(res);
                location.reload(); // for now
            }
        });
    });

    // Decrease
    $(document).on('click', '.sub-qty', function () {
        let id = $(this).data('id');

        $.ajax({
            url: "/cart/decrease",
            type: "POST",
            data: { id: id },
            success: function (res) {
                console.log(res);
                location.reload();
            }
        });
    });

    $(document).ready(function () {
        $('.handle').on('click', function () {
            $('#bottomSheet').toggleClass('active_bottom');
        });  
        $('.sheet-overlay').on('click', function () {
            $('#bottomSheet').removeClass('active_bottom');
        }); 

        $('.tab-link').click(function () {
            var tabID = $(this).data('tab');

            // remove active from all tabs
            $('.tab-link').removeClass('active');
            $('.tab-content').removeClass('active');

            // add active to clicked tab
            $(this).addClass('active');
            $('.' + tabID).addClass('active');
        });
    });
</script>
@endsection