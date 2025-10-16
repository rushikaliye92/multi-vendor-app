@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4 text-center">🛒 Your Cart</h2>

    <!-- Back Button -->
    <div class="mb-4 text-center">
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
            &laquo; Back to Products
        </a>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($grouped->isEmpty())
        <div class="alert alert-info text-center">Your cart is empty.</div>
    @else
        @foreach($grouped as $vendorId => $items)
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-header bg-primary text-white fw-bold">
                    Vendor: {{ $items->first()->product->vendor->name }}
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
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                    <tr>
                                        <td>{{ $item->product->name }}</td>
                                        <td class="text-center">
                                            <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex justify-content-center align-items-center">
                                                @csrf
                                                <input type="number" name="quantity" min="1" max="{{ $item->product->stock }}" value="{{ $item->quantity }}" class="form-control form-control-sm me-2" style="width:70px">
                                                <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                            </form>
                                        </td>
                                        <td class="text-end">₹{{ number_format($item->product->price, 2) }}</td>
                                        <td class="text-end">₹{{ number_format($item->quantity * $item->product->price, 2) }}</td>
                                        <td class="text-center">
                                            <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="text-end mb-4">
            <form action="{{ route('cart.checkout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success btn-lg">Proceed to Checkout</button>
            </form>
        </div>
    @endif
</div>
@endsection