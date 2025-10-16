@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4 text-center">📦 Order #{{ $order->id }}</h2>

    <!-- Order Summary -->
    <div class="card mb-5 shadow-sm border-0">
        <div class="card-header bg-primary text-white fw-bold">
            Order Summary
        </div>
        <div class="card-body">
            <div class="row gy-2">
                <div class="col-md-3"><strong>Vendor:</strong> {{ $order->vendor->name }}</div>
                <div class="col-md-3"><strong>Customer:</strong> {{ $order->user->name }}</div>
                <div class="col-md-3"><strong>Total:</strong> ₹{{ number_format($order->total, 2) }}</div>
                <div class="col-md-3">
                    <strong>Payment Status:</strong>
                    <span class="badge bg-{{ $order->payment->status == 'paid' ? 'success' : 'warning' }}">
                        {{ ucfirst($order->payment->status) }}
                    </span>
                </div>
            </div>
            <div class="mt-3"><strong>Order Date:</strong> {{ $order->created_at->format('d M Y, H:i') }}</div>
        </div>
    </div>

    <!-- Order Items -->
    <div class="card shadow-sm border-0 mb-5">
        <div class="card-header bg-secondary text-white fw-bold">
            Items in Your Order
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th class="text-center">Quantity</th>
                            <th class="text-end">Price</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->product->name }}</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-end">₹{{ number_format($item->price, 2) }}</td>
                                <td class="text-end">₹{{ number_format($item->quantity * $item->price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-end">
            <h5 class="mb-0 fw-bold">Total: ₹{{ number_format($order->total, 2) }}</h5>
        </div>
    </div>

    <div class="text-center">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-primary btn-lg">Back to Orders</a>
    </div>
</div>
@endsection