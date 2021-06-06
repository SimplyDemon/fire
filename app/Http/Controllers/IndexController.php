<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class IndexController extends Controller {
    protected $folderPath = 'page.index.';

    public function index() {
        $products   = Product::orderBy( 'id', 'desc' )->get();
        $categories = Category::orderBy( 'id', 'desc' )->has( 'products' )->get();


        return response()->view( $this->folderPath . 'index', [
            'products'   => $products,
            'categories' => $categories,
        ] );
    }
}
