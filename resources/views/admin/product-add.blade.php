@extends('layouts.admin')

@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Add Product</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li><a href="{{ route('admin.index') }}"><div class="text-tiny">Dashboard</div></a></li>
                <li><i class="icon-chevron-right"></i></li>
                <li><a href="{{ route('admin.products') }}"><div class="text-tiny">Products</div></a></li>
                <li><i class="icon-chevron-right"></i></li>
                <li><div class="text-tiny">Add product</div></li>
            </ul>
        </div>

        <form class="tf-section-2 form-add-product" method="POST" enctype="multipart/form-data" action="{{ route('admin.product.store') }}">
            @csrf
            <div class="wg-box">
                {{-- Product Name --}}
                <fieldset class="name">
                    <div class="body-title mb-10">Product name <span class="tf-color-1">*</span></div>
                    <input class="mb-10" type="text" placeholder="Enter product name" name="name" value="{{ old('name') }}" required>
                    <div class="text-tiny">Do not exceed 100 characters when entering the product name.</div>
                </fieldset>
                @error('name') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror

                {{-- Slug --}}
                <fieldset class="name">
                    <div class="body-title mb-10">Slug <span class="tf-color-1">*</span></div>
                    <input class="mb-10" type="text" placeholder="Enter product slug" name="slug" value="{{ old('slug') }}" required>
                </fieldset>
                @error('slug') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror

                <div class="gap22 cols">
                    {{-- Category --}}
                    <fieldset class="category">
                        <div class="body-title mb-10">Category <span class="tf-color-1">*</span></div>
                        <div class="select">
                            <select name="category_id">
                                <option value="">Choose category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </fieldset>
                    @error('category_id') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror

                    {{-- Brand --}}
                    <fieldset class="brand">
                        <div class="body-title mb-10">Brand <span class="tf-color-1">*</span></div>
                        <div class="select">
                            <select name="brand_id">
                                <option value="">Choose Brand</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </fieldset>
                    @error('brand_id') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror
                </div>

                {{-- Short Description --}}
                <fieldset class="shortdescription">
                    <div class="body-title mb-10">Short Description <span class="tf-color-1">*</span></div>
                    <textarea class="mb-10 ht-150" name="short_description" required>{{ old('short_description') }}</textarea>
                </fieldset>
                @error('short_description') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror

                {{-- Description --}}
                <fieldset class="description">
                    <div class="body-title mb-10">Description <span class="tf-color-1">*</span></div>
                    <textarea class="mb-10" name="description" required>{{ old('description') }}</textarea>
                </fieldset>
                @error('description') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror
            </div>

            <div class="wg-box">
                {{-- Single Image --}}
                <fieldset>
                    <div class="body-title">Upload Main Image <span class="tf-color-1">*</span></div>
                    <div class="upload-image flex-grow">
                        {{-- 🟩 Add this preview box --}}
                        <div id="imgpreview" class="item" style="display: none;">
                            <img src="#" alt="Main Image Preview" style="max-height: 120px;">
                        </div>
                        <div id="upload-file" class="item up-load">
                            <label class="uploadfile" for="myFile">
                                <span class="icon"><i class="icon-upload-cloud"></i></span>
                                <span class="body-text">Drop your image here or <span class="tf-color">click to browse</span></span>
                                <input type="file" id="myFile" name="image" accept="image/*">
                            </label>
                        </div>
                    </div>
                </fieldset>
                @error('image') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror

                {{-- Gallery Images --}}
                <fieldset>
                    <div class="body-title mb-10">Upload Gallery Images</div>
                    <div class="upload-image mb-16">
                        <div id="galUpload" class="item up-load">
                            <label class="uploadfile" for="gFile">
                                <span class="icon"><i class="icon-upload-cloud"></i></span>
                                <span class="text-tiny">Drop images or <span class="tf-color">click to browse</span></span>
                                <input type="file" id="gFile" name="images[]" accept="image/*" multiple>
                            </label>
                        </div>
                    </div>
                </fieldset>
                @error('images') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror
                @error('images.*') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror

                <div class="cols gap22">
                    {{-- Regular Price --}}
                    <fieldset class="name">
                        <div class="body-title mb-10">Regular Price <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Enter regular price" name="regular_price" value="{{ old('regular_price') }}" required>
                    </fieldset>
                    @error('regular_price') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror

                    {{-- Sale Price --}}
                    <fieldset class="name">
                        <div class="body-title mb-10">Sale Price</div>
                        <input class="mb-10" type="text" placeholder="Enter sale price" name="sale_price" value="{{ old('sale_price') }}">
                    </fieldset>
                    @error('sale_price') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror
                </div>

                <div class="cols gap22">
                    {{-- SKU --}}
                    <fieldset class="name">
                        <div class="body-title mb-10">SKU <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Enter SKU" name="SKU" value="{{ old('SKU') }}" required>
                    </fieldset>
                    @error('SKU') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror

                    {{-- Quantity --}}
                    <fieldset class="name">
                        <div class="body-title mb-10">Quantity <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Enter quantity" name="quantity" value="{{ old('quantity') }}" required>
                    </fieldset>
                    @error('quantity') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror
                </div>

                <div class="cols gap22">
                    {{-- Stock Status --}}
                    <fieldset class="name">
                        <div class="body-title mb-10">Stock</div>
                        <div class="select mb-10">
                            <select name="stock_status">
                                <option value="instock" {{ old('stock_status') == 'instock' ? 'selected' : '' }}>In Stock</option>
                                <option value="outofstock" {{ old('stock_status') == 'outofstock' ? 'selected' : '' }}>Out of Stock</option>
                            </select>
                        </div>
                    </fieldset>

                    {{-- Featured --}}
                    <fieldset class="name">
                        <div class="body-title mb-10">Featured</div>
                        <div class="select mb-10">
                            <select name="featured">
                                <option value="0" {{ old('featured') == '0' ? 'selected' : '' }}>No</option>
                                <option value="1" {{ old('featured') == '1' ? 'selected' : '' }}>Yes</option>
                            </select>
                        </div>
                    </fieldset>
                </div>

                {{-- Submit --}}
                <div class="cols gap10">
                    <button class="tf-button w-full" type="submit">Add product</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push("scripts")
<script>
    $(function () {
        // 🔄 Main image preview
        $('#myFile').on('change', function () {
            const file = this.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    $('#imgpreview img').attr('src', e.target.result);
                    $('#imgpreview').show();
                };
                reader.readAsDataURL(file);
            } else {
                $('#imgpreview').hide();
                $('#imgpreview img').attr('src', '');
            }
        });

        // 🖼️ Gallery image previews
        $('#gFile').on('change', function () {
            const galleryFiles = this.files;
            $('.gitems').remove(); // Remove existing previews

            Array.from(galleryFiles).forEach(file => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const previewHtml = `
                            <div class="item gitems">
                                <img src="${e.target.result}" class="effect8" style="max-height: 120px;">
                            </div>`;
                        $(previewHtml).insertBefore('#galUpload');
                    };
                    reader.readAsDataURL(file);
                }
            });
        });

        // 🆔 Auto-generate slug from name input
        $('input[name="name"]').on('input', function () {
            const name = $(this).val();
            $('input[name="slug"]').val(stringToSlug(name));
        });

        // 🔤 Slug generator
        function stringToSlug(text) {
            return text.toString().toLowerCase()
                .replace(/\s+/g, '-')           // Replace spaces with -
                .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
                .replace(/\-\-+/g, '-')         // Replace multiple - with single -
                .replace(/^-+/, '')             // Trim - from start of text
                .replace(/-+$/, '');            // Trim - from end of text
        }
    });
</script>

@endpush