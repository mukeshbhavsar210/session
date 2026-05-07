<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>@yield('title')</title>
	<link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/style.min.css') }}" />
	{{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
	<link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/style.css') }}" />
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;500&family=Raleway:ital,wght@0,400;0,600;0,800;1,200&family=Roboto+Condensed:wght@400;700&family=Roboto:wght@300;400;700;900&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
<div class="app-wrapper">		
	<header id="sticky-header">
		<div class="header">
			<div class="header__restaurant-name">
				<a href="{{ route('front.home') }}" class="logo" >
					<img style="width: 120px" src="{{ asset('front-assets/images/logo.jpg') }} " alt="" />
				</a>
			</div>
		</div>	
	</header>

	<section class="categories-section categories-section--medium-photo">
		<div class="categories-section__container">										
			@if(session('wishlist'))				
				<div class="menu-category">
					<ul class="menu-category__img">
						<li>
							<a href="{{ route('front.wishlist') }}">
								<img src="{{ asset('front-assets/images/favourite.png') }}" alt="Favourites">							
							</a>
							<p>Favourites</p>
						</li>
					</ul>					
				</div>
			@endif

			@if (getCategories()->isNotEmpty())
				@foreach (getCategories() as $value )	
					<div class="menu-category">	
						<ul class="menu-category__img">
							<li>
								<a href="{{ route('front.menu',[$value->slug])}}" class="
									{{
										(request()->routeIs('front.menu') && request()->segment(2) == $value->slug) ||
										(request()->routeIs('front.home') && $loop->first)
										? 'menu_active'
										: ''
									}}">
									@if ($value->image != "")
										<img src="{{ asset('uploads/category/'.$value->image) }} " alt="">								
									@endif									
								</a>								
								<p>{{ $value->name }}</p>
							</li>	
						</ul>
					</div>
				@endforeach				
			@endif
		</div>
	</section>
	@yield('content')		
</div>

<script src="{{ asset('front-assets/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('front-assets/js/bootstrap.bundle.5.1.3.min.js') }}"></script>
<script src="{{ asset('front-assets/js/documentReady.js') }}"></script>
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

    let selected = window.menuSelected;

	$('a[data-slug]').each(function () {
		if ($(this).data('slug') === selected) {
			$(this).addClass('active');
		}
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
		
		//Validation
		function checkFields() {
			let activeTab = $('.tab-link.active').data('tab');

			let notes      = $.trim($('textarea[name="notes"]').val());
			let dineinTime = $('select[name="dinein_time"]').val();
			let seatId     = $('select[name="seat_id"]').val();

			let time       = $('select[name="time"]').val();
			let name       = $.trim($('input[name="customer_name"]').val());
			let email      = $.trim($('input[name="customer_email"]').val());
			let phone      = $.trim($('input[name="customer_phone"]').val());
			let address    = $.trim($('textarea[name="address"]').val());

			let valid = false;

			// Dine in
			if (activeTab == 'tab1') {

				if (
					notes !== '' &&
					dineinTime !== '' &&
					seatId !== ''
				) {
					valid = true;
				}
			}

			// Takeaway
			else if (activeTab == 'tab2') {

				if (
					notes !== '' &&
					time !== '' &&
					name !== '' &&
					email !== '' &&
					phone !== ''
				) {
					valid = true;
				}
			}

			// Delivery
			else if (activeTab == 'tab3') {

				if (
					notes !== '' &&
					time !== '' &&
					name !== '' &&
					email !== '' &&
					phone !== '' &&
					address !== ''
				) {
					valid = true;
				}
			}

			if (valid) {
				$('.btn-primary').removeClass('disabled');
			} else {
				$('.btn-primary').addClass('disabled');
			}
		}

		// Inputs / textarea / select
		$('input, textarea, select').on('keyup change', checkFields);

		// Custom tab click
		$('.tab-link').on('click', function () {

			$('.tab-link').removeClass('active');
			$(this).addClass('active');

			checkFields();
		});

		// Initial check
		checkFields();
	});
</script>

@yield('customJs')

</body>
</html>