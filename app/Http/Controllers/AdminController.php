<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    // ========================== BRANDS ==========================

    public function brands()
    {
        $brands = Brand::orderBy('id', 'DESC')->paginate(10);
        return view('admin.brands', compact('brands'));
    }

    public function add_brand()
    {
        return view('admin.brand-add');
    }

    public function brand_store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:12',
            'slug' => 'required|unique:brands,slug|max:12',
            'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $brand = new Brand();
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);

        // Handle image
        $image = $request->file('image');
        $fileExtension = $image->getClientOriginalExtension();
        $fileName = Carbon::now()->timestamp . '.' . $fileExtension;

        $this->generateBrandThumbnail($image, $fileName);

        $brand->image = $fileName;
        $brand->save();

        return redirect()->route('admin.brands')->with('status', 'Brand has been added successfully!');
    }

    public function brand_edit($id)
    {
        $brand = Brand::findOrFail($id);
        return view('admin.brand-edit', compact('brand'));
    }

    public function brand_update(Request $request)
    {
        $request->validate([
            'name' => 'required|max:12',
            'slug' => 'required|max:12|unique:brands,slug,' . $request->id,
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $brand = Brand::findOrFail($request->id);
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);

        // Handle new image if uploaded
        if ($request->hasFile('image')) {
            $oldImage = public_path('uploads/brands/' . $brand->image);
            if (File::exists($oldImage)) {
                File::delete($oldImage);
            }

            $image = $request->file('image');
            $fileExtension = $image->getClientOriginalExtension();
            $fileName = Carbon::now()->timestamp . '.' . $fileExtension;

            $this->generateBrandThumbnail($image, $fileName);
            $brand->image = $fileName;
        }

        $brand->save();

        return redirect()->route('admin.brands')->with('status', 'Brand has been updated successfully!');
    }

    public function brand_delete($id)
    {
        $brand = Brand::findOrFail($id);

        $imagePath = public_path('uploads/brands/' . $brand->image);
        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }

        $brand->delete();

        return redirect()->route('admin.brands')->with('status', 'Brand has been deleted successfully!');
    }

    public function generateBrandThumbnail($image, $imageName)
    {
        $destinationPath = public_path('uploads/brands');

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $img = Image::read($image)->resize(300, 300);
        $img->cover(124, 124, 'top');
        $img->resize(124, 124, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath . '/' . $imageName);
    }

    // ========================== CATEGORIES ==========================

    public function categories()
    {
        $categories = Category::orderBy('id', 'DESC')->paginate(10);
        return view('admin.categories', compact('categories'));
    }

    public function add_category()
    {
        return view('admin.category-add');
    }

    public function add_category_store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|unique:categories,slug|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $fileExtension = $image->getClientOriginalExtension();
            $fileName = Carbon::now()->timestamp . '.' . $fileExtension;

            $this->generateCategoryThumbnailImage($image, $fileName);
            $category->image = $fileName;
        }

        $category->save();

        return redirect()->route('admin.categories')->with('status', 'Category has been added successfully!');
    }

    public function generateCategoryThumbnailImage($image, $imageName)
    {
        $destinationPath = public_path('uploads/categories');

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $img = Image::read($image)->resize(300, 300);
        $img->cover(124, 124, 'top');
        $img->resize(124, 124, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath . '/' . $imageName);
    }
}
