@extends('front.layouts.app')

@section('content')
    <section class="menu-products-section menu-products-section--grid">
        <div class="menu-grid">
            @if ($products->isNotEmpty())
                @foreach ($products as $product)
                    <x-products :product="$product" :seats="$seats" />
                @endforeach
            @endif
        
            <div class="col-md-12 pt-5">
                {{ $products->withQueryString()->links() }}
            </div>
        </div>                      
    </section>

    <x-cart :seats="$seats" />
    
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