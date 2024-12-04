@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <!-- Sorting and Header -->
    <form method="GET" action="{{ route('shop.index') }}" id="sortForm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="font-weight-bold">Products</h3>
            <div>
                <label for="sort" class="mr-2">Sort By:</label>
                <select id="sort" name="sort" class="form-control d-inline-block w-auto"
                    onchange="document.getElementById('sortForm').submit();">
                    <option value="recommend" {{ request('sort') == 'recommend' ? 'selected' : '' }}>Recommend</option>
                    <option value="price-low-high" {{ request('sort') == 'price-low-high' ? 'selected' : '' }}>Price: Low
                        to High</option>
                    <option value="price-high-low" {{ request('sort') == 'price-high-low' ? 'selected' : '' }}>Price: High
                        to Low</option>
                </select>
            </div>
        </div>
    </form>

    <!-- Product Grid -->
    <div class="row">
        @foreach($products as $product) <!-- Loop through the products -->
            <div class="col-md-4 mb-4"> <!-- 3 Columns per row -->
                <div class="card product-card shadow-sm">
                    <div class="position-relative">
                        <!-- Check if product has an image -->
                        <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="Product Image">
                        @if($product->sale_price)
                            <span class="badge badge-danger position-absolute top-0 left-0 m-2">
                                -{{ number_format((($product->price - $product->sale_price) / $product->price) * 100) }}%
                            </span>
                        @endif
                    </div>
                    <div class="card-body text-center">
                        <h6 class="card-title font-weight-bold">{{ $product->name }}</h6>
                        <p class="text-muted small">
                            <!-- Display available sizes with stock -->
                            {{ __('Available Sizes: ') }}
                            @foreach($product->sizes as $size => $stock)
                                @if($stock > 0) <!-- Only show sizes with stock -->
                                    {{($size) }}: {{ $stock }} &nbsp;
                                @endif
                            @endforeach
                        </p>
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
<!-- Your JavaScript -->
<script>
    console.log('DOMContentLoaded fired!');
    document.addEventListener('DOMContentLoaded', function () {
        console.log('DOMContentLoaded fired!');
        const sizeModal = document.getElementById('sizeModal');
        sizeModal.addEventListener('show.bs.modal', function (event) {
            console.log('Modal is about to be shown');
            const button = event.relatedTarget; // Button that triggered the modal
            const productId = button.getAttribute('data-product-id');
            const productName = button.getAttribute('data-product-name');
            console.log('Product ID:', productId);
            console.log('Product Name:', productName);
            const productSizes = JSON.parse(button.getAttribute('data-product-sizes'));

            console.log('Product Sizes:', productSizes);
            // Populate modal fields
            document.getElementById('productId').value = productId;
            document.getElementById('productName').textContent = productName;

            const sizeSelect = document.getElementById('sizeSelect');
            sizeSelect.innerHTML = ''; // Clear existing options

            // Populate size dropdown with available sizes
            Object.keys(productSizes).forEach(size => {
                if (productSizes[size] > 0) {
                    const option = document.createElement('option');
                    option.value = size;
                    option.textContent = `${size.toUpperCase()} (${productSizes[size]} in stock)`;
                    sizeSelect.appendChild(option);
                }
            });
        });
    });
</script>
@endsection