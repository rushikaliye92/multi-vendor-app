<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('vendor')->get()->groupBy('vendor.name');
        return view('products.index', compact('products'));
    }
}
