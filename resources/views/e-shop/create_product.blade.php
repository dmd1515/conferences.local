@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add New Product</h2>
    <form action="{{ route('store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="product_number" class="form-label">Product Number</label>
            <input type="text" name="product_number" id="product_number" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">Product Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" name="price" id="price" class="form-control" step="0.01" required>
        </div>
        <div class="mb-3">
            <label for="sale_price" class="form-label">Sale Price</label>
            <input type="number" name="sale_price" id="sale_price" class="form-control" step="0.01">
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Product Image</label>
            <input type="file" name="image" id="image" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Sizes and Stock</label>
            <div class="row">
                <div class="col-md-4">
                    <label for="size_s">S</label>
                    <input type="number" name="sizes[s]" id="size_s" class="form-control" min="0" value="0">
                </div>
                <div class="col-md-4">
                    <label for="size_m">M</label>
                    <input type="number" name="sizes[m]" id="size_m" class="form-control" min="0" value="0">
                </div>
                <div class="col-md-4">
                    <label for="size_l">L</label>
                    <input type="number" name="sizes[l]" id="size_l" class="form-control" min="0" value="0">
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Add Product</button>
    </form>
</div>
@endsection
