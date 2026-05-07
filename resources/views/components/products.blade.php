@props(['product', 'popularProducts', 'seats'])

@php
    $productImage = $product->product_images->first();
@endphp

<div class="menu-product">
    <div class="menu-product__item">
        @php
            $cart = session('cart', []);
        @endphp

        @if(isset($cart[$product->id]))                            
            <div class="menu-product__item__ordered_qty">
                {{ $cart[$product->id]['quantity'] }}
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
                <a href="{{ route('addwishlist', $product->id ) }}" class="wishlist-icon">
                    <span class="sprites"></span>                                    
                </a>                                
            @endif
        </div>

        {{-- Product Image --}}
        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#bottomModal_{{ $product->id }}" class="product-img">
            <div class="menu-product__item__img">
                @if (!empty($productImage->image))
                    <img src="{{ asset('uploads/product/large/'.$productImage->image) }}" alt="{{ $product->name }}">
                @endif
            </div>
        </a>

        {{-- Product Name + Price --}}
        <div class="menu-product__item__top-block">            
            <p>{{ $product->name }}</p>
            <p>₹{{ $product->price }}</p>
        </div>
    </div>
</div>

<div class="modal fade" id="bottomModal_{{ $product->id }}" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="menuContainer">                                    
                <div class="product-pic">
                    @php
                        $productImage = $product->product_images->first();
                    @endphp

                    @if (!empty($productImage->image))
                        <img src="{{ asset('uploads/product/large/'.$productImage->image) }}" alt="{{ $product->name }}" >
                    @else
                        <img src="{{ asset('admin-assets/img/default-150x150.png') }}" alt="{{ $product->name }}" />
                    @endif 
                </div>                                    
                
                <div class="btnControl flex-justify">
                    <a href="javascript:0" class="back-icon" data-bs-dismiss="modal" aria-label="Close">
                        <span class="sprites"></span>                                                            
                    </a>
                    <a href="{{ route('addwishlist', $product->id ) }}" class="wishlist-icon">
                        <span class="sprites"></span>                                                             
                    </a>                                                
                </div> 
                <div class="product-title flex-justify">
                    <h2>{{ $product->name }}</h2>
                    <div class="product-price">
                        <span class="price"> ₹{{ $product->price }}</span>
                        @if ($product->discounted_price > 0)
                            <span class="price text-secondary"><del> ₹{{ $product->discounted_price }}</del></span>
                        @endif
                    </div>
                </div>
                <div class="product-details">   
                    @php
                        $cart = session('cart', []);
                        $qty = isset($cart[$product->id]) ? $cart[$product->id]['quantity'] : 0;
                    @endphp

                    @if($qty > 0)                                            
                        <div class="add-controls">
                            <a href="javascript:0" class="sub-qty products-sub" data-id="{{ $product->id }}">
                                <span class="sprites"></span>
                            </a>

                            <div class="manage-qty">{{ $qty }}</div>                                                    

                            <a href="javascript:0" class="add-qty products-add" data-id="{{ $product->id }}">
                                <span class="sprites"></span>
                            </a>
                        </div>
                    @else
                        <a href="{{ route('front.addCart', $product->id ) }}" class="add-to-cart-button product-add products-add">
                            <span class="sprites"></span>                                                    
                        </a>
                    @endif

                    <p>{{ \Illuminate\Support\Str::limit(strtolower($product->description), 50) }}</p>

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