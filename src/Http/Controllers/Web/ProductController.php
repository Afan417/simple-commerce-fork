<?php

namespace Damcclean\Commerce\Http\Controllers\Web;

use Damcclean\Commerce\Facades\Product;
use Statamic\View\View;

class ProductController extends Controller
{
    public function index()
    {
        return (new View)
            ->template('commerce.products')
            ->layout('layout');
    }

    public function show($product)
    {
        $product = Product::findBySlug($product);

        if ($product['enabled'] == false) {
            abort(404);
        }

        return (new View)
            ->template('commerce.product')
            ->layout('layout')
            ->with($product->toArray());
    }
}
