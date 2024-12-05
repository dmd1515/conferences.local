<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>

    <script>
        // Adding an item to the cart (AJAX request)
        $(document).on('click', '.add-to-cart', function () {
            $.ajax({
                url: '{{ route('cart.add') }}',
                method: 'POST',
                data: {
                    product_id: $(this).data('product-id'),
                    size: $(this).data('size'),
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    // Update the cart count in the badge
                    $('#cartCount').text(response.cartCount);
                }
            });
        });

        // Removing an item from the cart (AJAX request)
        $(document).on('click', '.remove-from-cart', function () {
            var itemId = $(this).data('item-id');
            $.ajax({
                url: '/cart/' + itemId,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function (response) {
                    // Update the cart count in the badge
                    $('#cartCount').text(response.cartCount);

                    // Optionally, remove the item from the cart UI
                    $('#cartItem-' + itemId).remove();
                }
            });
        });
    </script>

</head>

<body class="@yield('body-class')">
    <div id="app">
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @elseif(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container">
                <!-- Brand -->
                <div class="navbar-brand-container">
                    <a class="navbar-brand" href="{{ url('/shop') }}">
                        {{ __('NightStar') }}
                    </a>
                </div>

                <!-- Collapsible Content -->
                <div class="navbar" id="navbarSupportedContent">
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto align-items-center">
                        @guest
                            <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary me-3">
                                <i class="fas fa-shopping-cart"></i>
                                <span class="badge bg-danger" id="cartCount">{{ $cartCount }}</span>
                            </a>
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <div class="d-flex align-items-center">
                                <!-- Cart Button -->
                                @if(Auth::check() && Auth::user()->admin)
                                    <!-- Show "Create New Item" Button for Admin -->
                                    <a href="{{ route('shop.create') }}" class="btn btn-primary me-3">
                                        <i class="fas fa-plus"></i> Create New Item
                                    </a>
                                @else
                                    <!-- Show Cart Button for Normal Users -->
                                    <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary me-3">
                                        <i class="fas fa-shopping-cart"></i>
                                        <span class="badge bg-danger" id="cartCount">{{ $cartCount }}</span>
                                    </a>
                                @endif

                                <!-- User Dropdown -->
                                <div class="nav-item dropdown">
                                    <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        {{ Auth::user()->name }}
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="dropdownMenuButton1">
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                                                                                        document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </ul>
                                </div>
                            </div>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main>
            @yield('content')
        </main>
    </div>
    @yield('scripts')
</body>

</html>