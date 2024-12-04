@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Your Cart</h3>

    @if($cartItems->isEmpty())
        <p>Your cart is empty!</p>
    @else
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
    @endif
</div>
@endsection
