@extends('front.layouts.app')

@section('content')
    <section class="menu-products-section menu-products-section--grid">
        <div class="menu-grid">
            @if ($products->isNotEmpty())
                @foreach ($products as $value)
                    <div class="menu-product">
                        <div class="menu-product__item">                            
                            @php
                                $productImage = $value->product_images->first();
                            @endphp

                            <a href="" data-bs-toggle="modal" data-bs-target="#bottomModal_{{ $value->id }}" class="product-img">
                                <div class="menu-product__item__img">
                                    @if (!empty($productImage->image))
                                        <img src="{{ asset('uploads/product/large/'.$productImage->image) }}" alt="{{ $value->name }}" >
                                    @endif  
                                </div>
                            </a>
                            
                            <div class="menu-product__item__top-block">
                                <div class="menu-product__item__name text-overflow">
                                    <p>{{ $value->name }}</p>
                                    <p class="mb-0">₹{{ $value->price }}</p>
                                </div>
                            </div>             
                            
                            <div class="modal fade" id="bottomModal_{{ $value->id }}" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-fullscreen">
                                    <div class="modal-content">
                                        <div class="menuContainer">
                                            <div class="product-pic">
                                                @if (!empty($productImage->image))
                                                    <img src="{{ asset('uploads/product/large/'.$productImage->image) }}" alt="{{ $value->name }}" class="rounded img-fluid" >
                                                @else
                                                    <img src="{{ asset('admin-assets/img/default-150x150.png') }}" alt="{{ $value->name }}" height="90" class="rounded" />
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
                                            
                                            <div class="product-title">
                                                <div>
                                                    <h2>{{ $value->name }}</h2>                                                                                                                                                              
                                                </div>
                                                <div>
                                                    <span class="price">₹{{ $value->price }}</span>
                                                    @if ($value->discounted_price > 0)
                                                        <del class="text-muted"> ₹{{ $value->discounted_price }}</del>
                                                    @endif   
                                                </div>                                           
                                            </div>

                                            <div class="product-details">
                                                <p>{!! $value->description !!}</p> 
                                                <p class="mb-0 mt-2">{{ $value->category->name }}</p> 
                                                <a class="product-add pull-right" href="javascript:void(0);" onclick="addToCart({{ $value->id }})">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2Z"></path></svg>
                                                </a>                                                                                              
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
        
            <div class="col-md-12 pt-5">
                {{ $products->withQueryString()->links() }}
            </div>
        </div>                      
    </div>
@endsection

@section('customJs')
<script>

jQuery(document).ready(function($) {
//   setTimeout(function() {
//     $('#lab-slide-bottom-popup').modal('show');
//   }, 10000);

  $(document).ready(function() {
    $('.lab-slide-up').find('a').attr('data-toggle', 'modal');
    $('.lab-slide-up').find('a').attr('data-target', '#lab-slide-bottom-popup');
  });

});

    rangeSlider = $(".js-range-slider").ionRangeSlider({
        type: "double",
        min: 0,
        max: 1000,
        from: {{ ($priceMin) }},
        to: {{ ($priceMax) }},
        step: 10,
        skin: "round",
        max_position: "+",
        prefix: "₹",
        onFinish: function(){
            apply_filters()
        }
    });

    var slider = $(".js-range-slider").data("ionRangeSlider");

    $("#sort").change(function(){
        apply_filters()
    });


    function apply_filters(){
        var brands = [];
        $(".brand-label").each(function(){
            if ($(this).is(":checked") == true){
                brands.push($(this).val());
            }
        });

        var url = '{{ url()->current() }}?';

        //Brand filter
        if (brands.length > 0) {
            url += '&brand='+brands.toString();
        }

        //Price range filter
        url += '&price_min='+slider.result.from+'&price_max='+slider.result.to;

        //Sorting filter
        var keyword = $('#search').val();
        if(keyword.length > 0){
            url += '&search='+keyword;
        }

        url += '&sort='+$("#sort").val();

        window.location.href = url;
    }
</script>

@endsection