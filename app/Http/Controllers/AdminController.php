<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Brand;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\Laravel\Facades\Image;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

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
            'name' => 'required|string|max:255',
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

    protected function generateBrandThumbnail($image, $imageName)
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
}
