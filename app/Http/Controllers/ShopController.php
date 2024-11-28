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
    public function index()
    {
        return view('e-shop.shop');
    }
    public function create()
{
    return view('e-shop.create_product');
}
public function store(Request $request)
{
   // dd($request->all());
    $request->validate([
        'product_number' => 'required|unique:products',
        'name' => 'required',
        'price' => 'required|numeric',
        'sale_price' => 'nullable|numeric',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'sizes' => 'required|array',
        'sizes.*' => 'numeric|min:0', // Validate stock for each size
    ]);
    \Log::info('Form Data: ', $validatedData);
    // Handle image upload
    $imagePath = null;
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('images/products', 'public');
    }

    Product::create([
        'product_number' => $request->product_number,
        'name' => $request->name,
        'price' => $request->price, 
        'sale_price' => $request->sale_price,
        'image' => $imagePath,
        'sizes' => $request->sizes, // Array of sizes and stock
    ]);

    return redirect()->route('shop.index')->with('success', 'Product added successfully.');
}
}
