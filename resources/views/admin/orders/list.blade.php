@extends('admin.layouts.app')

@section('content')

<section class="content-header">
    <div class="row">
        <div class="col-sm-6">
            <h1>Orders <span class="count">{{ $orderCount }}</span></h1>
        </div>
        <div class="col-sm-6 text-right">

        </div>
    </div>
</section>

@include('admin.layouts.message')
<div class="card">
    <form action="" method="get" >
        <div class="card-header">
            <div class="card-title">
                <button type="button" onclick="window.location.href='{{ route('orders.index') }}'" class="btn btn-default btn-sm">Reset</button>
            </div>

            <div class="card-tools">
                <div class="input-group input-group" style="width: 250px;">
                    <input value="{{ Request::get('keyword') }}" type="text" name="keyword" class="form-control float-right" placeholder="Search">

                    <div class="input-group-append">
                    <button type="submit" class="btn btn-default">
                        <i class="fas fa-search"></i>
                    </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="card-body table-responsive mt-3 p-0">
        <ul class="nav nav-tabs" role="tablist">
            @if($dineinCount > 0)
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab">Dinein - (<b>{{ $dineinCount }}</b>)</a>
                </li>
            @endif

            @if($takeawayCount > 0)
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#tabs-2" role="tab">Takeaway - (<b>{{ $takeawayCount }}</b>)</a>
                </li>
            @endif

            @if($deliveryCount > 0)
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#tabs-3" role="tab">Delivery - (<b>{{ $deliveryCount }}</b>)</a>
                </li>
            @endif
        </ul>

        <div class="tab-content">
            @if($dineinCount > 0)
                <div class="tab-pane active" id="tabs-1" role="tabpanel">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Order#</th>
                                <th>Ordered items</th>
                                <th>Ready time</th>
                                <th>Table</th>
                                <th>Notes</th>                                    
                                <th>Amount</th>
                                <th>Total</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($orderItems->isNotEmpty())
                                @foreach ($orderItems as $value)
                                    @if($value->orders->order_type == 'Dinein')
                                        <tr>
                                            <td><a href="{{ route('orders.detail',$value->order_id) }}"><img style="width: 30px; border-radius:100px; margin-right:5px;" src="{{ asset('uploads/product/'.$value->image) }}" ></a> {{ $value->order_id }}</td>
                                            <td>{{ $value->name }} - {{ $value->qty }}</td>
                                            <td>{{ $value->orders->ready_time }}</td>
                                            <td>
                                                {{-- {{ $value->seat->table_name }}  --}}
                                                @if ($value->orders->status == 'running')
                                                    <span class="badge bg-danger">Running</span>
                                                @elseif ($value->orders->status == 'pending')
                                                    <svg class="text-danger h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                @elseif ($value->orders->status == 'shipped')
                                                    <span class="badge bg-info">Shipped</span>
                                                @elseif ($value->orders->status == 'delivered')
                                                    <svg class="text-success-500 h-6 w-6 text-success" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                @else
                                                    <span class="badge bg-danger">Cancelled</span>
                                                @endif
                                            </td>
                                            <td>{{ $value->orders->notes }}</td>
                                            <td>₹ {{ number_format($value->price,2) }}</td>
                                            <td>₹ {{ number_format($value->price*$value->qty,2) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($value->created_at->format('d M, Y')) }}</td>
                                        </tr>
                                    @endif
                                @endforeach 
                            @else
                                <tr>
                                    <td colspan="5">No Order found</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            @endif
            
            @if($takeawayCount > 0)
                <div class="tab-pane" id="tabs-2" role="tabpanel">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Order#</th>
                                <th>Ordered items</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Ready time</th>
                                <th>Notes</th>                                    
                                <th>Amount</th>
                                <th>Total</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($orderItems->isNotEmpty())
                                @foreach ($orderItems as $value)
                                    @if($value->orders->order_type == 'Takeaway')
                                        <tr>
                                            <td><a href="{{ route('orders.detail',$value->order_id) }}">{{ $value->order_id }}</a></td>
                                            <td>{{ $value->name }} - {{ $value->qty }}</td>
                                            <td>{{ $value->orders->ready_time }}</td>
                                            <td>{{ $value->orders->takeaway_name }}</td>
                                            <td>{{ $value->orders->takeaway_phone }}</td>
                                            <td>{{ $value->orders->takeaway_email }}</td>
                                            <td>{{ $value->orders->notes }}</td>
                                            <td>₹ {{ number_format($value->price,2) }}</td>
                                            <td>₹ {{ number_format($value->price*$value->qty,2) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($value->created_at->format('d M, Y')) }}</td>
                                        </tr>
                                    @endif
                                @endforeach 
                            @else
                                <tr>
                                    <td colspan="5">Records not found</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            @endif

            @if($deliveryCount > 0)
                <div class="tab-pane" id="tabs-3" role="tabpanel">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Order#</th>
                                <th>Ordered items</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Ready time</th>
                                <th>Notes</th>                                    
                                <th>Amount</th>
                                <th>Total</th>
                                <th>Payment</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($orderItems->isNotEmpty())
                                @foreach ($orderItems as $value)
                                    @if($value->orders->order_type == 'Delivery')
                                        <tr>
                                            <td><a href="{{ route('orders.detail',$value->order_id) }}">{{ $value->order_id }}</a></td>
                                            <td>{{ $value->name }} - {{ $value->qty }}</td>
                                            <td>{{ $value->orders->delivery_name }}</td>
                                            <td>{{ $value->orders->delivery_phone }}</td>
                                            <td>{{ $value->orders->delivery_email }}</td>
                                            <td>{{ $value->orders->ready_time }}</td>
                                            <td>Table no. {{ $value->orders->table_number }}</td>
                                            <td>{{ $value->orders->notes }}</td>
                                            <td>₹ {{ number_format($value->price,2) }}</td>
                                            <td>₹ {{ number_format($value->price*$value->qty,2) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($value->created_at->format('d M, Y')) }}</td>
                                        </tr>
                                    @endif
                                @endforeach 
                            @else
                                <tr>
                                    <td colspan="5">Records not found</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <div class="card-footer clearfix">
        {{-- {{ $orders->links() }} --}}
    </div>
</div>
    
@endsection
