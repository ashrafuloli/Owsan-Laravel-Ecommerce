<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Brand;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        if (request()->has('type') && request()->input('type') == 'all') {
            $productCategories = Brand::withTrashed()->orderBy('created_at', 'desc')->paginate(8);
        } elseif (request()->has('type') && request()->input('type') == 'trash') {
            $productCategories = Brand::onlyTrashed()->orderBy('created_at', 'desc')->paginate(8);
        } else {
            $productCategories = Brand::orderBy('created_at', 'desc')->paginate(8);
        }
        return view('admin.brand.index', compact('productCategories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.brand.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $brand = new Brand();
        $brand->name = $request->input('name');
        $brand->description = $request->input('description');

        // slug generation
        $uniqueSlug = Str::slug($request->input('name'));
        $next = 2;
        while (Brand::whereSlug($uniqueSlug)->first()) {
            $uniqueSlug = Str::slug($request->input('name')) . '-' . $next;
            $next++;
        }
        $brand->slug = $uniqueSlug;

        // Thumbnail Upload
        if ($request->has('thumbnail')) {
            $thumbnail = $request->file('thumbnail');
            $path = 'uploads/images/product-brands/';
            $thumbnailName = time() . '-' . rand(100, 999) . '_' . $thumbnail->getClientOriginalName();
            $thumbnail->move(public_path($path), $thumbnailName);
            $brand->thumbnail = $thumbnailName;
        }

        if ($brand->save()) {
            return redirect()->route('admin.brand.edit', $brand->id)->with('success', __('Product brand Added.'));
        }
        return redirect()->back()->with('error', __('Please try again.'));
    }

    /**
     * Display the specified resource.
     *
     * @param Brand $brand
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|Response|\Illuminate\View\View
     */
    public function show(Brand $brand)
    {
        return view('admin.brand.show', compact('brand'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Brand $brand
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|Response|\Illuminate\View\View
     */
    public function edit(Brand $brand)
    {
        return view('admin.brand.edit', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Brand $brand
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Brand $brand)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $brand->name = $request->input('name');
        $brand->description = $request->input('description');
        $brand->status = $request->input('status');

        // slug generation
        $uniqueSlug = Str::slug($request->input('name'));
        $next = 2;
        while (Brand::whereSlug($uniqueSlug)->first()) {
            if ($request->input('name') == $brand->name) {
                $uniqueSlug = $brand->slug;
                break;
            }

            // isdirty method to check if the model was changed after loaded
//            if ($brand->isDirty('name')){
//                $uniqueSlug = $brand->slug;
//                break;
//            }

            $uniqueSlug = Str::slug($request->input('name')) . '-' . $next;
            $next++;
        }
        $brand->slug = $uniqueSlug;

        // Thumbnail Upload
        if ($request->has('thumbnail')) {
            // old delete
            if ($brand->thumbnail) {
                File::delete($brand->thumbnail);
            }

            $thumbnail = $request->file('thumbnail');
            $path = 'uploads/images/product-categories/';
            $thumbnailName = time() . '-' . rand(100, 999) . '_' . $thumbnail->getClientOriginalName();
            $thumbnail->move(public_path($path), $thumbnailName);
            $brand->thumbnail = $thumbnailName;
        }

        if ($brand->save()) {
            return redirect()->route('admin.brand.edit', $brand->id)->with('success', __('Product brand Updated.'));
        }
        return redirect()->back()->with('error', __('Please try again.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Brand $brand
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Brand $brand)
    {
        if ($brand->delete()) {
            return redirect()->back()->with('success', __('Product brand Deleted.'));
        }
        return redirect()->back()->with('error', __('Please try again.'));
    }

    public function restore($id)
    {
        $brand = Brand::onlyTrashed()->findOrFail($id);
        if($brand){
            if ($brand->restore()) {
                return redirect()->back()->with('success', __('Product category restored.'));
            }
            return redirect()->back()->with('error', __('Please try again.'));
        }
        return redirect()->back()->with('error', __('No product to restore.'));
    }

    public function force_delete($id)
    {
        $brand = Brand::onlyTrashed()->findOrFail($id);
        if($brand){
            if ($brand->thumbnail){
                File::delete($brand->thumbnail);
            }
            if ($brand->forceDelete()) {
                return redirect()->back()->with('success', __('Product category permanently deleted.'));
            }
            return redirect()->back()->with('error', __('Please try again.'));
        }
        return redirect()->back()->with('error', __('No product to delete.'));
    }

    public function bulk_delete( Request $request ) {
        $item_ids = $request->input( 'item_ids' );
        foreach ( $item_ids as $id ) {
            $brand = Brand::find( $id );
            if ( $brand ) {
                $brand->delete();
            }
        }
        return response()->json( [
            'message' => 'success',
        ] );
    }

    public function bulk_force_delete( Request $request ) {
        $item_ids = $request->input( 'item_ids' );
        foreach ( $item_ids as $id ) {
            $brand = Brand::withTrashed()->find( $id );
            if ( $brand ) {
                if ( $brand->thumbnail ) {
                    File::delete( $brand->thumbnail );
                }
                $brand->forceDelete();
            }
        }
        return response()->json( [
            'message' => 'success',
        ] );
    }

    public function bulk_restore( Request $request ) {
        $item_ids = $request->input( 'item_ids' );
        foreach ( $item_ids as $id ) {
            $brand = Brand::withTrashed()->find( $id );
            if ( $brand ) {
                $brand->restore();
            }
        }
        return response()->json( [
            'message' => 'success',
        ] );
    }
}
