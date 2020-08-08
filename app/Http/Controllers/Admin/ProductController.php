<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->has('type') && request()->input('type') == 'all') {
            $products = Product::withTrashed()->latest()->paginate(8);
        } elseif (request()->has('type') && request()->input('type') == 'trash') {
            $products = Product::onlyTrashed()->latest()->paginate(8);
        } else {
            $products = Product::latest()->paginate(8);
        }
        return view('admin.product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.product.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $product = new Product();
        $product->name = $request->input('name');
        $product->description = $request->input('description');

        // slug generation
        $uniqueSlug = Str::slug($request->input('name'));
        $next = 2;
        while (Product::whereSlug($uniqueSlug)->first()) {
            $uniqueSlug = Str::slug($request->input('name')) . '-' . $next;
            $next++;
        }
        $product->slug = $uniqueSlug;

        // Thumbnail Upload
        if ($request->has('thumbnail')) {
            $thumbnail = $request->file('thumbnail');
            $path = 'uploads/images/product-products/';
            $thumbnailName = time() . '-' . rand(100, 999) . '_' . $thumbnail->getClientOriginalName();
            $thumbnail->move(public_path($path), $thumbnailName);
            $product->thumbnail = $thumbnailName;
        }

        if ($product->save()) {
            return redirect()->route('admin.product.edit', $product->id)->with('success', __('Product product Added.'));
        }
        return redirect()->back()->with('error', __('Please try again.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return view('admin.product.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        return view('admin.product.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->status = $request->input('status');

        // slug generation
        $uniqueSlug = Str::slug($request->input('name'));
        $next = 2;
        while (Product::whereSlug($uniqueSlug)->first()) {
            if ($request->input('name') == $product->name) {
                $uniqueSlug = $product->slug;
                break;
            }

            // isdirty method to check if the model was changed after loaded
//            if ($product->isDirty('name')){
//                $uniqueSlug = $product->slug;
//                break;
//            }

            $uniqueSlug = Str::slug($request->input('name')) . '-' . $next;
            $next++;
        }
        $product->slug = $uniqueSlug;

        // Thumbnail Upload
        if ($request->has('thumbnail')) {
            // old delete
            if ($product->thumbnail) {
                File::delete($product->thumbnail);
            }

            $thumbnail = $request->file('thumbnail');
            $path = 'uploads/images/product-categories/';
            $thumbnailName = time() . '-' . rand(100, 999) . '_' . $thumbnail->getClientOriginalName();
            $thumbnail->move(public_path($path), $thumbnailName);
            $product->thumbnail = $thumbnailName;
        }

        if ($product->save()) {
            return redirect()->route('admin.product.edit', $product->id)->with('success', __('Product product Updated.'));
        }
        return redirect()->back()->with('error', __('Please try again.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Product $product)
    {
        if ($product->delete()) {
            return redirect()->back()->with('success', __('Product product Deleted.'));
        }
        return redirect()->back()->with('error', __('Please try again.'));
    }

    public function restore($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        if ($product) {
            if ($product->restore()) {
                return redirect()->back()->with('success', __('Product category restored.'));
            }
            return redirect()->back()->with('error', __('Please try again.'));
        }
        return redirect()->back()->with('error', __('No product to restore.'));
    }

    public function force_delete($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        if ($product) {
            if ($product->thumbnail) {
                File::delete($product->thumbnail);
            }
            if ($product->forceDelete()) {
                return redirect()->back()->with('success', __('Product category permanently deleted.'));
            }
            return redirect()->back()->with('error', __('Please try again.'));
        }
        return redirect()->back()->with('error', __('No product to delete.'));
    }

    public function bulk_delete(Request $request)
    {
        $item_ids = $request->input('item_ids');
        foreach ($item_ids as $id) {
            $product = Product::find($id);
            if ($product) {
                $product->delete();
            }
        }
        return response()->json([
            'message' => 'success',
        ]);
    }

    public function bulk_force_delete(Request $request)
    {
        $item_ids = $request->input('item_ids');
        foreach ($item_ids as $id) {
            $product = Product::withTrashed()->find($id);
            if ($product) {
                if ($product->thumbnail) {
                    File::delete($product->thumbnail);
                }
                $product->forceDelete();
            }
        }
        return response()->json([
            'message' => 'success',
        ]);
    }

    public function bulk_restore(Request $request)
    {
        $item_ids = $request->input('item_ids');
        foreach ($item_ids as $id) {
            $product = Product::withTrashed()->find($id);
            if ($product) {
                $product->restore();
            }
        }
        return response()->json([
            'message' => 'success',
        ]);
    }

    public function bulk_active(Request $request)
    {
        $item_ids = $request->input('item_ids');
        foreach ($item_ids as $id) {
            $product = Product::withTrashed()->find($id);
            if ($product) {
                $product->status = true;
                $product->save();
            }
        }
        return response()->json([
            'message' => 'success'
        ]);
    }

    public function bulk_inactive(Request $request)
    {
        $item_ids = $request->input('item_ids');
        foreach ($item_ids as $id) {
            $product = Product::withTrashed()->find($id);
            if ($product) {
                $product->status = false;
                $product->save();
            }
        }
        return response()->json([
            'message' => 'success'
        ]);
    }
}
