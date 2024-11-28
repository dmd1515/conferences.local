@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <!-- Sorting and Header -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="font-weight-bold">Products</h3>
            <div>
                <label for="sort" class="mr-2">Sort By:</label>
                <select id="sort" class="form-control d-inline-block w-auto">
                    <option value="recommend">Recommend</option>
                    <option value="price-low-high">Price: Low to High</option>
                    <option value="price-high-low">Price: High to Low</option>
                </select>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="row">
            <!-- Card 1 -->
            <div class="col-md-4 mb-4"> <!-- 3 Columns per row -->
                <div class="card product-card shadow-sm">
                    <div class="position-relative">
                        <img src="https://via.placeholder.com/200" class="card-img-top" alt="Product Image">
                        <span class="badge badge-danger position-absolute top-0 left-0 m-2">-15%</span>
                    </div>
                    <div class="card-body text-center">
                        <h6 class="card-title font-weight-bold">Product 1</h6>
                        <p class="text-muted small">Lorem ipsum dolor sit amet.</p>
                        <div class="price mb-2">
                            <span class="text-primary font-weight-bold">$99.99</span>
                            <del class="text-muted">$119.99</del>
                        </div>
                        <button class="btn btn-sm btn-primary">Add to Cart</button>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="col-md-4 mb-4">
                <div class="card product-card shadow-sm">
                    <div class="position-relative">
                        <img src="https://via.placeholder.com/200" class="card-img-top" alt="Product Image">
                        <span class="badge badge-danger position-absolute top-0 left-0 m-2">-15%</span>
                    </div>
                    <div class="card-body text-center">
                        <h6 class="card-title font-weight-bold">Product 2</h6>
                        <p class="text-muted small">Lorem ipsum dolor sit amet.</p>
                        <div class="price mb-2">
                            <span class="text-primary font-weight-bold">$99.99</span>
                            <del class="text-muted">$119.99</del>
                        </div>
                        <button class="btn btn-sm btn-primary">Add to Cart</button>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="col-md-4 mb-4">
                <div class="card product-card shadow-sm">
                    <div class="position-relative">
                        <img src="https://via.placeholder.com/200" class="card-img-top" alt="Product Image">
                        <span class="badge badge-danger position-absolute top-0 left-0 m-2">-15%</span>
                    </div>
                    <div class="card-body text-center">
                        <h6 class="card-title font-weight-bold">Product 3</h6>
                        <p class="text-muted small">Lorem ipsum dolor sit amet.</p>
                        <div class="price mb-2">
                            <span class="text-primary font-weight-bold">$99.99</span>
                            <del class="text-muted">$119.99</del>
                        </div>
                        <button class="btn btn-sm btn-primary">Add to Cart</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
