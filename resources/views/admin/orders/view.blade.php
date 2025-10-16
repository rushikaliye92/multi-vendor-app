@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>Order #{{ $order->id }} Placed Successfully!</h2>

    <!-- Order Summary -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">
            Order Summary
        </div>
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-3"><strong>Vendor:</strong> {{ $order->vendor->name }}</div>
                <div class="col-md-3"><strong>Customer:</strong> {{ $order->user->name }}</div>
                <div class="col-md-3"><strong>Total:</strong> ${{ number_format($order->total, 2) }}</div>
                <div class="col-md-3">
                    <strong>Payment Status:</strong>
                    <span class="badge bg-{{ $order->payment->status == 'paid' ? 'success' : 'warning' }}">
                        {{ ucfirst($order->payment->status) }}
                    </span>
                </div>
            </div>
            <div class="mb-2"><strong>Order Date:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</div>
        </div>
    </div>

    <!-- Order Items -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-secondary text-white">
            Items
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>₹{{ number_format($item->price, 2) }}</td>
                            <td>₹{{ number_format($item->quantity * $item->price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer text-end">
            <h5 class="mb-0">Total: ₹{{ number_format($order->total, 2) }}</h5>
        </div>
    </div>

    <a href="{{ route('products') }}" class="btn btn-outline-primary">Back to Products</a>
</div>
@endsection