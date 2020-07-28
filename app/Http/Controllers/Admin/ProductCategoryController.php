<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @noinspection PhpUndefinedMethodInspection
     */
    public function index()
    {
        if (request()->has('type') && request()->input('type') == 'trash'){
            $productCategories = ProductCategory::onlyTrashed()->orderBy('created_at', 'desc')->paginate(8);
        }else{
            $productCategories = ProductCategory::orderBy('created_at', 'desc')->paginate(8);
        }
        return view('admin.product-category.index', compact('productCategories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.product-category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $productCategory = new ProductCategory();
        $productCategory->name = $request->input('name');
        $productCategory->description = $request->input('description');

        // slug generation
        $uniqueSlug = Str::slug($request->input('name'));
        $next = 2;
        while (ProductCategory::whereSlug($uniqueSlug)->first()) {
            $uniqueSlug = Str::slug($request->input('name')) . '-' . $next;
            $next++;
        }
        $productCategory->slug = $uniqueSlug;

        // Thumbnail Upload
        if ($request->has('thumbnail')) {
            $thumbnail = $request->file('thumbnail');
            $path = 'uploads/images/product-categories/';
            $thumbnailName = time() . '-' . rand(100, 999) . '_' . $thumbnail->getClientOriginalName();
            $thumbnail->move(public_path($path), $thumbnailName);
            $productCategory->thumbnail = $thumbnailName;
        }

        if ($productCategory->save()) {
            return redirect()->route('admin.product-category.edit', $productCategory->id)->with('success', __('Product category Added.'));
        }
        return redirect()->back()->with('error', __('Please try again.'));
    }

    /**
     * Display the specified resource.
     *
     * @param ProductCategory $productCategory
     * @return void
     */
    public function show(ProductCategory $productCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param ProductCategory $productCategory
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(ProductCategory $productCategory)
    {
        return view('admin.product-category.edit', compact('productCategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param ProductCategory $productCategory
     * @return void
     */
    public function update(Request $request, ProductCategory $productCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ProductCategory $productCategory
     * @return void
     */
    public function destroy(ProductCategory $productCategory)
    {
        //
    }
}
