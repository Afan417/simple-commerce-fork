<?php

namespace DoubleThreeDigital\SimpleCommerce\Http\Controllers\Cp;

use DoubleThreeDigital\SimpleCommerce\Http\Requests\ProductCategoryRequest;
use DoubleThreeDigital\SimpleCommerce\Models\Product;
use DoubleThreeDigital\SimpleCommerce\Models\ProductCategory;
use Statamic\CP\Breadcrumbs;
use Statamic\Facades\Blueprint;
use Statamic\Http\Controllers\CP\CpController;

class ProductCategoryController extends CpController
{
    public function index()
    {
        $this->authorize('view', ProductCategory::class);

        $crumbs = Breadcrumbs::make([
            ['text' => 'Simple Commerce'],
        ]);

        $categories = ProductCategory::paginate(config('statamic.cp.pagination_size'));

        return view('commerce::cp.product-categories.index', [
            'crumbs' => $crumbs,
            'categories' => $categories,
            'createUrl' => (new ProductCategory())->createUrl(),
        ]);
    }

    public function create()
    {
        $this->authorize('create', ProductCategory::class);

        $crumbs = Breadcrumbs::make([
            ['text' => 'Simple Commerce'],
            ['text' => 'Product Categories', 'url' => cp_route('product-categories.index')],
        ]);

        $blueprint = Blueprint::find('simple-commerce/product_category');

        $fields = $blueprint->fields();
        $fields = $fields->addValues([]);
        $fields = $fields->preProcess();

        return view('commerce::cp.product-categories.create', [
            'blueprint' => $blueprint->toPublishArray(),
            'values'    => $fields->values(),
            'meta'      => $fields->meta(),
            'crumbs'    => $crumbs,
        ]);
    }

    public function store(ProductCategoryRequest $request)
    {
        $this->authorize('create', ProductCategory::class);

        $category = new ProductCategory();
        $category->title = $request->title;
        $category->slug = $request->slug;
        $category->save();

        return ['redirect' => cp_route('product-categories.edit', ['category' => $category->uuid])];
    }

    public function show(ProductCategory $category)
    {
        $this->authorize('view', $category);

        $crumbs = Breadcrumbs::make([
            ['text' => 'Simple Commerce'],
            ['text' => 'Product Categories', 'url' => cp_route('product-categories.index')],
        ]);

        $products = Product::where('product_category_id', $category->id)
            ->paginate(config('statamic.cp.pagination_size'));

        return view('commerce::cp.product-categories.show', [
            'crumbs' => $crumbs,
            'products' => $products,
            'category' => $category,
            'createUrl' => (new Product())->createUrl(),
        ]);
    }

    public function edit(ProductCategory $category)
    {
        $this->authorize('edit', $category);

        $crumbs = Breadcrumbs::make([
            ['text' => 'Simple Commerce'],
            ['text' => 'Product Categories', 'url' => cp_route('product-categories.index')],
        ]);

        $blueprint = Blueprint::find('simple-commerce/product_category');

        $fields = $blueprint->fields();
        $fields = $fields->addValues([]);
        $fields = $fields->preProcess();

        return view('commerce::cp.product-categories.edit', [
            'blueprint' => $blueprint->toPublishArray(),
            'values'    => $category,
            'meta'      => $fields->meta(),
            'crumbs'    => $crumbs,
        ]);
    }

    public function update(ProductCategoryRequest $request, ProductCategory $category): ProductCategory
    {
        $this->authorize('update', $category);

        $category->title = $request->title;
        $category->slug = $request->slug;
        $category->save();

        return $category;
    }

    public function destroy(ProductCategory $category)
    {
        $this->authorize('delete', $category);

        if (ProductCategory::count() === 1) {
            return back()->with('error', 'You can\'t delete the only category.');
        }

        // TODO: decide what we should do with products in this category

        $category->delete();

        return back()
            ->with('success', "$category->title has been deleted.");
    }
}
