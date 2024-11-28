<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ShopController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
{
    $sortOption = $request->input('sort', 'recommend'); // Default to 'recommend'

    switch ($sortOption) {
        case 'price-low-high':
            $products = Product::orderBy('price', 'asc')->get();
            break;
        case 'price-high-low':
            $products = Product::orderBy('price', 'desc')->get();
            break;
        default:
            // Default sorting logic (e.g., by 'created_at' or some other field)
            $products = Product::orderBy('created_at', 'desc')->get();
            break;
    }

    return view('e-shop.shop', compact('products'));
}

    /**
     * Show the product creation page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        return view('e-shop.create_product');
    }

    /**
     * Store a newly created product in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the product data
        $request->validate([
            'product_number' => 'required|unique:products',
            'name' => 'required',
            'price' => 'required|numeric',
            'sale_price' => 'nullable|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sizes' => 'required|array',
            'sizes.s' => 'required|numeric|min:0',  // Validate 'S' size
            'sizes.m' => 'required|numeric|min:0',  // Validate 'M' size
            'sizes.l' => 'required|numeric|min:0',  // Validate 'L' size
        ]);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images/products', 'public');
        }

        // Create the product in the database
        Product::create([
            'product_number' => $request->product_number,
            'name' => $request->name,
            'price' => $request->price, 
            'sale_price' => $request->sale_price,
            'image' => $imagePath,
            'sizes' => $request->sizes, // Array of sizes and stock
        ]);

        // Redirect back with a success message
        return redirect()->route('shop.index')->with('success', 'Product added successfully.');
    }
}
