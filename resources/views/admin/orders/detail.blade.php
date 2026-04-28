@extends('admin.layouts.app')

@section('content')
<section class="content-header">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Order: #{{ $order->id }}</h1>
        </div>
        <div class="col-sm-6 text-right">
            <a href="{{ route('orders.index') }}" class="btn btn-primary">Back</a>
        </div>
    </div>
</section>
    
<section>
    <div class="row">
        @include('admin.layouts.message')
        <div class="col-md-9">
            <div class="card">
                <div class="card-header pt-3">
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
                </div>
            </div>
            <div class="card">
                    <div class="card-body table-responsive p-3">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th class="text-right" width="100">Price</th>
                                    <th class="text-center" width="100">Qty</th>
                                    <th class="text-right" width="100">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orderItems as $value)
                                    <tr>
                                        <td>{{ $value->name }}</td>
                                        <td class="text-right">₹ {{ number_format($value->price,2) }}</td>
                                        <td class="text-center">{{ $value->qty }}</td>
                                        <td class="text-right">₹ {{ number_format($value->total,2) }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="3" class="text-right">Subtotal:</td>
                                    <td class="text-right">₹ {{ number_format($order->total,2) }}</td>
                                </tr>
                                {{-- <tr>
                                    <td colspan="3" class="text-right">Discount: {{ (!empty($order->coupon_code)) ? '('.$order->coupon_code.')' : '' }}</td>
                                    <td class="text-right">₹ {{ number_format($order->discount,2) }}</td>
                                </tr> --}}
                                <tr>
                                    <td colspan="3" class="text-right">GST:</td>
                                    <td class="text-right">{{ $order->gst }}%</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-right">SCGT:</td>
                                    <td class="text-right">{{ $order->sgst }}%</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-right">CGST:</td>
                                    <td class="text-right">{{ $order->cgst }}%</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-right">Shipping:</td>
                                    <td class="text-right">₹ {{ number_format($order->shipping,2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-right">Grand Total:</td>
                                    @php
                                        {{ $gstCal=($order->total/(($order->gst/100)+1)); }}
                                        {{ $shppingAdd=($order->shipping+$order->total); }}

                                        // $taxrate=18; // 22%
                                        // $price=790;
                                        // $priceMinusTax=($price/(($taxrate/100)+1));
                                    @endphp
                                    <th class="text-right">₹ {{ number_format($shppingAdd + $gstCal,2) }}</th>

                                    {{-- {{ $gstCal }} -- 
                                    {{ $priceMinusTax }} --}}
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
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
                                <label for="shipped_date">Shipped Date</label>
                                <input placeholder="Shipped Date" autocomplete="off" value="{{ $order->shipped_date }}" type="text" name="shipped_date" id="shipped_date" class="form-control">
                            </div>

                            <div class="mb-3">
                                <button class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="" method="post" name="sendInvoiceEmail" id="sendInvoiceEmail">
                            <h2 class="h4 mb-3">Send Inovice Email</h2>
                            <div class="mb-3">
                                <select name="userType" id="userType" class="form-control">
                                    <option value="customer">Customer</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-primary">Send</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    </div>
</section>
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
