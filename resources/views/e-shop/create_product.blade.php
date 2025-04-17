@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>{{ isset($product) ? 'Edit Product' : 'Add New Product' }}</h2>

        {{-- Product Create/Update Form --}}
        <form action="{{ isset($product) ? route('product.update', $product->id) : route('store') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @if(isset($product))
                @method('PUT')
            @endif

            <div class="mb-3">
                <label for="product_number" class="form-label">Product Number</label>
                <input type="text" name="product_number" id="product_number" class="form-control" required
                    value="{{ old('product_number', $product->product_number ?? '') }}">
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Product Name</label>
                <input type="text" name="name" id="name" class="form-control" required
                    value="{{ old('name', $product->name ?? '') }}">
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" name="price" id="price" class="form-control" step="0.01" required
                    value="{{ old('price', $product->price ?? '') }}">
            </div>

            <div class="mb-3">
                <label for="sale_price" class="form-label">Sale Price</label>
                <input type="number" name="sale_price" id="sale_price" class="form-control" step="0.01"
                    value="{{ old('sale_price', $product->sale_price ?? '') }}">
            </div>

            @if(isset($product) && $product->image)
                <div class="mb-3">
                    <label class="form-label d-block">Current Image:</label>
                    <img src="{{ asset('storage/' . $product->image) }}" alt="Current Product Image" class="img-thumbnail mb-2"
                        style="max-height: 200px;">
                </div>
            @endif

            <div class="mb-3">
                <label class="form-label">Sizes and Stock</label>
                <div class="row">
                    <div class="col-md-4">
                        <label for="size_s">S</label>
                        <input type="number" name="sizes[s]" id="size_s" class="form-control" min="0"
                            value="{{ old('sizes.s', $product->sizes['s'] ?? 0) }}">
                    </div>
                    <div class="col-md-4">
                        <label for="size_m">M</label>
                        <input type="number" name="sizes[m]" id="size_m" class="form-control" min="0"
                            value="{{ old('sizes.m', $product->sizes['m'] ?? 0) }}">
                    </div>
                    <div class="col-md-4">
                        <label for="size_l">L</label>
                        <input type="number" name="sizes[l]" id="size_l" class="form-control" min="0"
                            value="{{ old('sizes.l', $product->sizes['l'] ?? 0) }}">
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Product Image</label>
                <input type="file" name="image" id="image" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">
                {{ isset($product) ? 'Update Product' : 'Add Product' }}
            </button>
        </form>

        {{-- Delete Form --}}
        @if(isset($product))
            <form action="{{ route('product.destroy', $product->id) }}" method="POST" class="mt-3">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"
                    onclick="return confirm('Are you sure you want to delete this product?')">
                    Delete Product
                </button>
            </form>
        @endif
    </div>
@endsection