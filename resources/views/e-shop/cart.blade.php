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
                        @php
                            $sizeStock = $item->product->sizes[$item->size] ?? 0; // Stock for the specific size
                        @endphp
                        <tr>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ strtoupper($item->size) }}</td>
                            <td>
                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <div class="input-group">
                                        <button type="submit" name="action" value="decrease"
                                            class="btn btn-outline-secondary btn-sm" {{ $item->quantity <= 1 ? 'disabled' : '' }}>-</button>
                                        <input type="text" value="{{ $item->quantity }}"
                                            class="form-control form-control-sm text-center" disabled>
                                        <button type="submit" name="action" value="increase"
                                            class="btn btn-outline-secondary btn-sm" {{ $item->quantity >= $sizeStock ? 'disabled' : '' }}>+</button>
                                    </div>
                                </form>
                            </td>
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

            <!-- Pay Button -->
            <form action="{{ route('payment.success') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary mt-2">Complete Payment</button>
            </form>
        </div>
    @endif

    <!-- You Might Be Interested Section -->
    <div class="mt-5">
        <h3 class="mb-4">You Might Be Interested</h3>
        <div class="row">
            @foreach($recommendedProducts as $product)
                <div class="col-md-3 mb-4">
                    <div class="card product-card shadow-sm">
                        <div class="position-relative">
                            <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="Product Image">
                            @if($product->sale_price)
                                <span class="badge badge-danger position-absolute top-0 left-0 m-2">
                                    -{{ number_format((($product->price - $product->sale_price) / $product->price) * 100) }}%
                                </span>
                            @endif
                        </div>
                        <div class="card-body text-center">
                            <h6 class="card-title font-weight-bold">{{ $product->name }}</h6>
                            <div class="price mb-2">
                                <span
                                    class="text-primary font-weight-bold">${{ number_format($product->sale_price ?? $product->price, 2) }}</span>
                                @if($product->sale_price)
                                    <del class="text-muted">${{ number_format($product->price, 2) }}</del>
                                @endif
                            </div>
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#sizeModal"
                                data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}"
                                data-product-sizes="{{ json_encode($product->sizes) }}">
                                Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>

<!-- Size Selection Modal -->
<div class="modal fade" id="sizeModal" tabindex="-1" aria-labelledby="sizeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sizeModalLabel">Select Size</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addToCartForm" method="POST" action="{{ route('cart.add') }}">
                    @csrf
                    <input type="hidden" name="product_id" id="productId">

                    <p id="productName"></p>

                    <div class="mb-3">
                        <label for="sizeSelect" class="form-label">Choose a size:</label>
                        <select name="size" id="sizeSelect" class="form-select" required>
                            <!-- Options will be populated dynamically -->
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Add to Cart</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/shop.js') }}"></script>
@endsection