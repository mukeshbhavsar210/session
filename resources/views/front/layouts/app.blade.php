<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>@yield('title')</title>
	<link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/style.min.css') }}" />
	<link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/style.css') }}" />
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;500&family=Raleway:ital,wght@0,400;0,600;0,800;1,200&family=Roboto+Condensed:wght@400;700&family=Roboto:wght@300;400;700;900&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bodyClass">
	<div class="page">
		<div id="menu-page" class="page" style="display:flex;flex-direction:column;top:0px;">
			<header id="sticky-header">
				<div class="header">
					<div class="header__restaurant-name">
						<a href="{{ route('front.home') }}" class="logo"><img style="width: 120px" src="{{ asset('front-assets/images/logo.jpg') }} " alt=""></a>
						<div class="float-end">
							@if (Route::has('login'))
								@auth
									<a href="{{ url('/dashboard') }}" target="_blank" >Dashboard</a>
								@else
									<a href="{{ route('login') }}" target="_blank" >Log in</a>
									@if (Route::has('register'))
										<a href="{{ route('register') }}" target="_blank" >Register</a>
									@endif
								@endauth
							@endif
						</div>
					</div>
				</div>	
			</header>

			<section class="categories-section categories-section--medium-photo">
				<div class="categories-section__container">							
					@if(session('wishlist'))
						<div class="menu-category">	
							<div class="menu-category__img">
								<a href="{{  route('front.wishlist') }}">
									<img src="https://instalacarte.com/media/cache/emoji_small/emoji/favourite.png?v3" alt="Favourites">
									<div class="menu-category__name no-wrap"><div>
										Favourites 
									</div>
								</a>
							</div>
						</div>
					</div>
				@endif
					@if (getCategories()->isNotEmpty())
						@foreach (getCategories() as $value )	
							<div class="menu-category">	
								<div class="menu-category__img">
									<a href="{{ route('front.menu',[$value->slug])}}" >
										@if ($value->image != "")
											<img src="{{ asset('uploads/category/'.$value->image) }} " alt="">
										@else
											<img src="{{ asset('admin-assets/img/default-150x150.png') }}" alt="" />
										@endif
									</a>	
								</div>
								<div class="menu-category__name no-wrap">
									<div><a href="{{ route('front.menu',[$value->slug])}}" >{{ $value->name }}</a></div>
								</div>
							</div>
						@endforeach
					@endif
				</div>
			</section>
    		@yield('content')
		</div>
	</div>

<script src="{{ asset('front-assets/js/jquery-3.7.1.js') }}"></script>
<script src="{{ asset('front-assets/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('front-assets/js/custom.js') }}"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

	function addToWishlist(id){
        $.ajax({
            url: '{{ route("front.addToWishlist",) }}',
            type: 'post',
            data: {id:id},
            dataType: 'json',
            success: function(response){
                if(response.status == true){
                    $("#wishlistModal .modal-body").html(response.message);
                    $("#wishlistModal").modal('show');
                } else {
                    window.location.href= "{{ route('front.home') }}";
                    alert(response.message);
                }
            }
        })
    }

	$(document).ready(function() {
		var slider_width = $('.orderDetails').height();
		$('#cartDetails').click(function() {
			if($(this).css("margin-bottom") == slider_width+"px" && !$(this).is(':animated')) {
				$('.orderDetails,#cartDetails').animate({"margin-bottom": '-='+slider_width});	
				$('body').removeClass("open");		
			}
			else {
				$('body').addClass("open");
					if(!$(this).is(':animated')) {
						$('.orderDetails,#cartDetails').animate({"margin-bottom": '+='+slider_width});				
					}
				}
			});
		});   
</script>
@yield('customJs')

</body>
</html>