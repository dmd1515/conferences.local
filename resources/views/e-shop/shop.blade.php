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
            @foreach($products as $product)
                <div class="col-md-4 mb-4">
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
                            <p class="text-muted small">
                                {{ __('Available Sizes: ') }}
                                @foreach($product->sizes as $size => $stock)
                                    @if($stock > 0)
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
                            <div class="d-flex justify-content-center align-items-center gap-2 flex-wrap mt-3">
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#sizeModal"
                                    data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}"
                                    data-product-sizes="{{ json_encode($product->sizes) }}">
                                    Add to Cart
                                </button>

                                @if(Auth::check() && Auth::user()->admin)
                                    <a href="{{ route('product.edit', ['id' => $product->id]) }}" class="btn btn-sm btn-warning">
                                        Edit
                                    </a>
                                @endif
                            </div>


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
    <script src="{{ asset('js/shop.js') }}"></script>
@endsection