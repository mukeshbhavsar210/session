@extends('front.layouts.app')

@section('content')

<section class="menu-products-section menu-products-section--grid">
    @if($popularProducts->isNotEmpty())    
        <div class="menu-grid">
            @foreach($popularProducts as $product)
                <x-products :product="$product" />
            @endforeach
        </div>
    @endif
</section>   

<x-cart :seats="$seats" />

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
                     window.location.href="{{ route('front.menu') }}"
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
</script>
@endsection