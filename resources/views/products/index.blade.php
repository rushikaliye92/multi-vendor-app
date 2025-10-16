@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-5 text-center">Explore Our Diverse Collection of Products</h2>

    @foreach($products as $vendorName => $vendorProducts)
        <div class="card mb-5 shadow-sm">
            <div class="card-header bg-primary text-white fw-bold">
                {{ $vendorName }}
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @foreach($vendorProducts as $product)
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">{{ $product->name }}</h5>
                                    <p class="mb-1"><strong>Price:</strong> ₹{{ number_format($product->price, 2) }}</p>
                                    <p class="mb-3"><strong>Stock:</strong> {{ $product->stock }}</p>
                                    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mt-auto">
                                        @csrf
                                        <div class="input-group">
                                            <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="form-control">
                                            <button type="submit" class="btn btn-success">Add to Cart</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection