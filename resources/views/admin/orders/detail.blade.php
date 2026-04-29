@extends('admin.layouts.app')

@section('content')

@include('admin.layouts.message')

<div class="card">
    <div class="card-body">
        <div class="row">                
            <div class="col-md-10 col-8">
                <div class="page-title"> 
                    <h4>Order: {{ $order->id }}</h4>                                               
                </div>
            </div>
            <div class="col-md-2 col-4 float-end">
                <a href="{{ route('orders.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>

    <div class="row">        
        <div class="col-md-9">            
            <div class="row invoice-info">
                <div class="col-sm-8 invoice-col">                            
                    @if($order->order_type == "Dinein")
                        <h1 class="h5 mb-3">Dinein Order</h1>
                        {{ $order->table_name }}
                    @elseif ($order->order_type == "Takeaway")
                        <h1 class="h5 mb-3">Takeaway Order</h1>
                        <address>
                            <p><b>{{ $order->takeaway_name }}</b><br>
                            Phone: {{ $order->takeaway_phone }}<br>
                            Email: {{ $order->takeaway_email }}</p>
                        </address>
                    @else
                        <h1 class="h5 mb-3">Shipping Address</h1>
                        <address>
                            <p><b>{{ $order->delivery_name }}</b><br>
                            {{ $order->address }}<br>
                            Phone: {{ $order->delivery_phone }}<br>
                            Email: {{ $order->delivery_email }}</p>
                        </address>
                    @endif
                </div>
                <div class="col-sm-4">
                    <div class="row">
                        <div class="col-sm-6 text-right">Invoice</div>
                        <div class="col-sm-6"><b>#000{{ $order->id }}</b></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 text-right"><span>Order ID:</span></div>
                        <div class="col-sm-6"><b>{{ $order->id }}</b></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 text-right"><span>Total:</span></div>
                        <div class="col-sm-6"><b>₹ {{ number_format($order->total,2) }}</b></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 text-right"><span>Status:</span></div>
                        <div class="col-sm-6">
                            @if ($order->status == 'running')
                                <span class="badge bg-danger">Running</span>
                            @elseif ($order->status == 'pending')
                                <span class="badge bg-danger">Pending</span>
                            @elseif ($order->status == 'shipped')
                                <span class="badge bg-info">Shipped</span>
                            @elseif ($order->status == 'delivered')
                                <span class="badge bg-success">Delivered</span>
                            @else
                                <span class="badge bg-danger">Cancelled</span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 text-right"><span>Shipped Date:</span></div>
                        <div class="col-sm-6">
                            <b>
                                @if (!empty($order->shipped_date))
                                    {{ \Carbon\Carbon::parse($order->shipped_date)->format('d M, y')}}
                                @else
                                    n/a
                                @endif
                            </b>
                        </div>
                    </div>
                </div>
            </div>
            <hr />

            <table class="table mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="border-top-0">Product</th>                            
                        <th class="border-top-0" width="10">Price</th>
                        <th class="border-top-0 text-end" width="100">Total</th>  
                    </tr>
                </thead>                     
                <tbody>  
                    @php
                        $subtotal = 0;
                        $gstAmount = 0;
                        $sgstAmount = 0;
                        $cgstAmount = 0;

                        foreach ($order->items as $item) {
                            $itemTotal = $item->price * $item->qty;
                            $subtotal += $itemTotal;

                            $gstAmount += ($itemTotal * $order->gst) / 100;
                            $sgstAmount += ($itemTotal * $order->sgst) / 100;
                            $cgstAmount += ($itemTotal * $order->cgst) / 100;
                        }

                        $grandTotal = $subtotal + $gstAmount + $sgstAmount + $cgstAmount + $order->shipping;
                    @endphp

                    @foreach ($orderItems as $value)
                        <tr>                            
                            <td>
                                 @php
                                    $productImage = optional($value->product?->product_images->first());
                                @endphp
                                
                                @if (!empty($productImage->image))
                                    <img src="{{ asset('uploads/product/'.$productImage->image) }}" height="60" class="me-3 rounded">
                                @else
                                    <img src="{{ asset('admin-assets/img/default-150x150.png') }}" height="60" class="me-3 rounded">
                                @endif
                                
                                {{ $value->name }} x {{ $value->qty }}
                            </td>
                            <td class="text-end">₹{{ round($value->price) }}</td>                            
                            <td class="text-end">₹{{ round($value->price * $value->qty) }}</td>
                        </tr>
                    @endforeach

                    <tr>
                        <td colspan="2" class="text-end"><b>Subtotal</b></td>
                        <td class="text-end">₹{{ round($subtotal) }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="text-end">GST ({{ $order->gst }}%)</td>
                        <td class="text-end">₹{{ round($gstAmount) }}</td>
                    </tr>                    
                    <tr>
                        <td colspan="2" class="text-end">SCGT ({{ $order->sgst }}%):</td>
                        <td class="text-end">₹{{ round($sgstAmount) }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="text-end">CGST ({{ $order->cgst }}%):</td>
                        <td class="text-end">₹{{ round($cgstAmount) }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="text-end">Shipping</td>
                        <td class="text-end">₹{{ round($order->shipping) }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="text-end">Total</td>
                        <th class="text-end">₹{{ round($grandTotal) }}</th>
                    </tr>                    
                </tbody>
            </table>                   
        </div>

        <div class="col-md-3">
            <div class="card">
                <form action="" method="post" name="changeOrderStatusForm" id="changeOrderStatusForm">
                    <div class="card-body">
                        <h2 class="h4 mb-3">Order Status</h2>
                        <div class="mb-3">
                            <select name="status" id="status" class="form-control">
                                <option value="available" {{ ($order->status == 'available') ? 'selected' : ''}}>Available</option>
                                <option value="running" {{ ($order->status == 'running') ? 'selected' : ''}}>Running</option>
                                <option value="pending" {{ ($order->status == 'pending') ? 'selected' : ''}}>Pending</option>
                                <option value="shipped" {{ ($order->status == 'shipped') ? 'selected' : ''}}>Shipped</option>
                                <option value="delivered" {{ ($order->status == 'delivered') ? 'selected' : ''}}>Delivered</option>
                                <option value="cancelled" {{ ($order->status == 'cancelled') ? 'selected' : ''}}>Cancelled</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="shipped_date">Date</label>
                            <input placeholder="Shipped Date" autocomplete="off" value="{{ $order->shipped_date }}" type="text" name="shipped_date" id="shipped_date" class="form-control">
                        </div>

                        <div class="mb-3">
                            <button class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('customJs')
    <script>
        $(document).ready(function(){
            $('#shipped_date').datetimepicker({
                format:'Y-m-d H:i:s',
            });
        });

        $("#changeOrderStatusForm").submit(function(event){
            event.preventDefault();
            var element = $(this);

            if (confirm("Are you sure you want to change status?")){
                $.ajax({
                    url: '{{ route("orders.changeOrderStatus",$order->id) }}',
                    type: 'post',
                    data: element.serializeArray(),
                    dataType: 'json',
                    success: function(response){
                        window.location.href='{{ route("orders.detail",$order->id ) }}';
                    }
                });
            }
        });

        $("#sendInvoiceEmail").submit(function(event){
            event.preventDefault();
            var element = $(this);

            if (confirm("Are you sure you want to send email?")){
                $.ajax({
                    url: '{{ route("orders.sendInvoiceEmail",$order->id) }}',
                    type: 'post',
                    data: element.serializeArray(),
                    dataType: 'json',
                    success: function(response){
                        window.location.href='{{ route("orders.detail",$order->id ) }}';
                    }
                });
            }
        });
    </script>
@endsection
