<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
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
        if (request()->has('type') && request()->input('type') == 'all') {
            $productCategories = ProductCategory::withTrashed()->orderBy('created_at', 'desc')->paginate(8);
        } elseif (request()->has('type') && request()->input('type') == 'trash') {
            $productCategories = ProductCategory::onlyTrashed()->orderBy('created_at', 'desc')->paginate(8);
        } else {
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
     * @param Request $request
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public function show(ProductCategory $productCategory)
    {
        return view('admin.product-category.show', compact('productCategory'));
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
     * @param Request $request
     * @param ProductCategory $productCategory
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, ProductCategory $productCategory)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $productCategory->name = $request->input('name');
        $productCategory->description = $request->input('description');
        $productCategory->status = $request->input('status');

        // slug generation
        $uniqueSlug = Str::slug($request->input('name'));
        $next = 2;
        while (ProductCategory::whereSlug($uniqueSlug)->first()) {
            if ($request->input('name') == $productCategory->name) {
                $uniqueSlug = $productCategory->slug;
                break;
            }

            // isdirty method to check if the model was changed after loaded
//            if ($productCategory->isDirty('name')){
//                $uniqueSlug = $productCategory->slug;
//                break;
//            }

            $uniqueSlug = Str::slug($request->input('name')) . '-' . $next;
            $next++;
        }
        $productCategory->slug = $uniqueSlug;

        // Thumbnail Upload
        if ($request->has('thumbnail')) {
            // old delete
            if ($productCategory->thumbnail) {
                File::delete($productCategory->thumbnail);
            }

            $thumbnail = $request->file('thumbnail');
            $path = 'uploads/images/product-categories/';
            $thumbnailName = time() . '-' . rand(100, 999) . '_' . $thumbnail->getClientOriginalName();
            $thumbnail->move(public_path($path), $thumbnailName);
            $productCategory->thumbnail = $thumbnailName;
        }

        if ($productCategory->save()) {
            return redirect()->route('admin.product-category.edit', $productCategory->id)->with('success', __('Product category Updated.'));
        }
        return redirect()->back()->with('error', __('Please try again.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ProductCategory $productCategory
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(ProductCategory $productCategory)
    {
        if ($productCategory->delete()) {
            return redirect()->back()->with('success', __('Product category Deleted.'));
        }
        return redirect()->back()->with('error', __('Please try again.'));
    }

    public function restore($id)
    {
        $productCategory = ProductCategory::onlyTrashed()->findOrFail($id);
        if($productCategory){
            if ($productCategory->restore()) {
                return redirect()->back()->with('success', __('Product category restored.'));
            }
            return redirect()->back()->with('error', __('Please try again.'));
        }
        return redirect()->back()->with('error', __('No product to restore.'));
    }

    public function force_delete($id)
    {
        $productCategory = ProductCategory::onlyTrashed()->findOrFail($id);
        if($productCategory){
            if ($productCategory->thumbnail){
                File::delete($productCategory->thumbnail);
            }
            if ($productCategory->forceDelete()) {
                return redirect()->back()->with('success', __('Product category permanently deleted.'));
            }
            return redirect()->back()->with('error', __('Please try again.'));
        }
        return redirect()->back()->with('error', __('No product to delete.'));
    }

    public function bulk_delete( Request $request ) {
        $item_ids = $request->input( 'item_ids' );
        foreach ( $item_ids as $id ) {
            $productCategory = ProductCategory::find( $id );
            if ( $productCategory ) {
                $productCategory->delete();
            }
        }
        return response()->json( [
            'message' => 'success',
        ] );
    }

    public function bulk_force_delete( Request $request ) {
        $item_ids = $request->input( 'item_ids' );
        foreach ( $item_ids as $id ) {
            $productCategory = ProductCategory::withTrashed()->find( $id );
            if ( $productCategory ) {
                if ( $productCategory->thumbnail ) {
                    File::delete( $productCategory->thumbnail );
                }
                $productCategory->forceDelete();
            }
        }
        return response()->json( [
            'message' => 'success',
        ] );
    }

    public function bulk_restore( Request $request ) {
        $item_ids = $request->input( 'item_ids' );
        foreach ( $item_ids as $id ) {
            $productCategory = ProductCategory::withTrashed()->find( $id );
            if ( $productCategory ) {
                $productCategory->restore();
            }
        }
        return response()->json( [
            'message' => 'success',
        ] );
    }
}
