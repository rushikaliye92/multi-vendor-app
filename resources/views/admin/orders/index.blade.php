@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4 text-center">📦 All Orders</h2>

    <!-- Filter Form -->
    <form method="GET" class="row g-3 mb-4 align-items-end">
        <div class="col-md-3">
            <label for="vendor_id" class="form-label fw-bold">Filter by Vendor</label>
            <select name="vendor_id" id="vendor_id" class="form-select">
                <option value="">All Vendors</option>
                @foreach($vendors as $vendor)
                    <option value="{{ $vendor->id }}" {{ request('vendor_id') == $vendor->id ? 'selected' : '' }}>
                        {{ $vendor->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label for="user_id" class="form-label fw-bold">Filter by Customer</label>
            <select name="user_id" id="user_id" class="form-select">
                <option value="">All Customers</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}" {{ request('user_id') == $customer->id ? 'selected' : '' }}>
                        {{ $customer->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label for="status" class="form-label fw-bold">Payment Status</label>
            <select name="status" id="status" class="form-select">
                <option value="">All Statuses</option>
                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            </select>
        </div>

        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-grow-1">Filter</button>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary flex-grow-1">Reset</a>
        </div>
    </form>

    <!-- Orders Table -->
    <div class="table-responsive shadow-sm rounded">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Vendor</th>
                    <th>Customer</th>
                    <th class="text-end">Total</th>
                    <th>Payment Status</th>
                    <th>Date</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>#{{ $order->id }}</td>
                        <td>{{ $order->vendor->name ?? 'N/A' }}</td>
                        <td>{{ $order->user->name ?? 'N/A' }}</td>
                        <td class="text-end">₹{{ number_format($order->total, 2) }}</td>
                        <td>
                            <span class="badge bg-{{ $order->payment?->status == 'paid' ? 'success' : 'warning' }}">
                                {{ ucfirst($order->payment?->status ?? 'pending') }}
                            </span>
                        </td>
                        <td>{{ $order->created_at->format('d M Y') }}</td>
                        <td class="text-center">
                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-info">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-3">No orders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4 d-flex justify-content-center">
        {{ $orders->links() }}
    </div>
</div>
@endsection