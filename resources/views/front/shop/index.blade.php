@extends('front.layouts.app')

@section('content')
    <div class="product-card">
        <div class="row">
            {{-- <section class="subcategories-section">
                <div class="subcategories-section__item no-wrap no-user-select subcategories-section__item--active">All</div>
                @if (getProducts()->isNotEmpty())
                    @foreach (getProducts() as $value )	
                        <div class="subcategories-section__item text-overflow" style="opacity: 1; transform: translateX(0px);">
                            <a href="{{ route('front.menu',[$value->slug])}}" class="no-user-select">{{ $value->name }}</a>
                        </div>
                    @endforeach
                @endif
            </section> --}}

            @if ($products->isNotEmpty())
                @foreach ($products as $value)
                    <div class="col-6 mb-3">
                        <div class="card ">
                            <div class="product-image position-relative">
                                <a href="" data-bs-toggle="modal" data-bs-target="#{{ $value->slug }}" class="product-img">
                                    @if (!empty($value->image))
                                        <img class="card-img-top" src="{{ asset('uploads/product/'.$value->image) }}" >
                                    @else
                                        <img class="card-img-top" src="{{ asset('admin-assets/img/default-150x150.png') }}" alt="" />
                                    @endif
                                </a>
                            </div>                            
                            <div class="row p-3">
                                <div class="col-8">{{ $value->title }}</div>
                                <div class="col-4">₹{{ $value->price }}</div>
                            </div>                            
                            <div class="modal fade" id="{{ $value->slug }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-fullscreen-sm-down">
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
                                                        <a type="button" class="link" data-bs-dismiss="modal" aria-label="Close">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"></path></svg>
                                                        </a>
                                                    </div>
                                                    <div class="col-3">
                                                        <a href="#" class="link right" >
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-suit-heart-fill" viewBox="0 0 16 16"><path d="M4 1c2.21 0 4 1.755 4 3.92C8 2.755 9.79 1 12 1s4 1.755 4 3.92c0 3.263-3.234 4.414-7.608 9.608a.513.513 0 0 1-.784 0C3.234 9.334 0 8.183 0 4.92 0 2.755 1.79 1 4 1z"></path></svg>
                                                        </a>
                                                    </div>
                                                </div> 
                                            </div>
                                            
                                            <div class="product-title">
                                                <div class="row">
                                                    <div class="col-9">
                                                        <h3>{{ $value->title }}</h3>
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
                                                <a class="product-add" href="javascript:void(0);" onclick="addToCart({{ $value->id }})">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2Z"></path></svg>
                                                </a>
                                                
                                                <p>{!! $value->description !!}</p>
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