@extends('front.layouts.app')

@section('content')

<div class="menu-content--categories-medium-photo menu-content">
    <section class="menu-products-section menu-products-section--grid">
        <div class="menu-grid">
            @if(session('wishlist'))
                @foreach(session('wishlist') as $id => $details)
                    <div class="menu-product">
                        <div class="menu-product__item">
                            <div class="wishlist">
                                <a href="{{ route('clear_wishlist', $id) }}" class="btn--icon--active">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="#222222" stroke-width="1.2px" x="0px" y="0px" viewBox="-1 -2 14 13" xml:space="preserve"><path d="M11,1c-0.6-0.6-1.5-1-2.3-1C7.8,0,7,0.4,6.3,1L6,1.3L5.7,1C5,0.3,4.2,0,3.3,0S1.6,0.3,1,1C0.3,1.6,0,2.4,0,3.3S0.3,5,1,5.7
                                        l4.8,4.8C5.9,10.6,6,10.6,6,10.6c0.1,0,0.2,0,0.2-0.1L11,5.7c0.6-0.6,1-1.5,1-2.4S11.7,1.6,11,1z"></path></svg>
                                </a>
                            </div>
                            <div class="menu-product__item__img">
                                <img src="{{ asset('uploads/product/'.$details['image']) }}" >  
                            </div>
                            <div class="menu-product__item__top-block">
                                <div class="menu-product__item__name text-overflow">{{ $details['name'] }}</p></div>
                                <div class="menu-product__item__price no-wrap">
                                    ₹  {{ $details['price'] }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </section>
</div>
@endsection