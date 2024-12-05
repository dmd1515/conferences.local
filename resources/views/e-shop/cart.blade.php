@extends('layouts.app')

@section('content')
<div class="container mt-4">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <h3 class="mb-4">Your Cart</h3>

    @if($cartItems->isEmpty())
        <p>Your cart is empty!</p>
    @else
        <form action="{{ route('cart.applyPromo') }}" method="POST" class="mb-4">
            @csrf
            <div class="form-group">
                <label for="promo_code">Promo Code</label>
                <input type="text" name="promo_code" id="promo_code" class="form-control" placeholder="Enter promo code"
                    required>
            </div>
            <button type="submit" class="btn btn-success btn-sm mt-2">Apply Promo Code</button>
        </form>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Size</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cartItems as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ strtoupper($item->size) }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>${{ number_format($item->product->sale_price ?? $item->product->price, 2) }}</td>
                        <td>
                            <form action="{{ route('cart.destroy', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            <h4>Total: ${{ number_format($cartTotal, 2) }}</h4>

            @if($promoDiscount > 0)
                <h5>Discount Applied: -${{ number_format($cartTotal - $discountedTotal, 2) }}</h5>
                <form action="{{ route('cart.removePromo') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-warning btn-sm mt-2">Remove Promo Code</button>
                </form>
            @endif

            <h4>Amount to Pay: ${{ number_format($discountedTotal, 2) }}</h4>

            <!-- Pay Button (redirects to success page) -->
            <form action="{{ route('payment.success') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary mt-2">Complete Payment</button>
            </form>
        </div>
    @endif
</div>
@endsection