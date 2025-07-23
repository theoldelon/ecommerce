@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Brand Information</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{ route('admin.index') }}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li><i class="icon-chevron-right"></i></li>
                <li>
                    <a href="{{ route('admin.brand.add') }}">
                        <div class="text-tiny">Brands</div>
                    </a>
                </li>
                <li><i class="icon-chevron-right"></i></li>
                <li><div class="text-tiny">New Brand</div></li>
            </ul>
        </div>

        <!-- new-brand -->
        <div class="wg-box">
            <form class="form-new-product form-style-1" action="{{ route('admin.brand.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Brand Name --}}
                <fieldset class="name">
                    <div class="body-title">Brand Name <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" name="name" placeholder="Brand name" value="{{ old('name') }}" required>
                </fieldset>
                @error('name')
                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                @enderror

                {{-- Brand Slug --}}
                <fieldset class="name">
                    <div class="body-title">Brand Slug <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" name="slug" placeholder="Brand slug" value="{{ old('slug') }}" required>
                </fieldset>
                @error('slug')
                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                @enderror

                {{-- Upload Image --}}
                <fieldset>
                    <div class="body-title">Upload Image <span class="tf-color-1">*</span></div>
                    <div class="upload-image flex-grow">
                        {{-- Preview Image --}}
                        <div class="item" id="imgpreview" style="display: none;">
                            <img src="#" class="effect8" alt="Image preview" style="max-width: 124px; border-radius: 5px;">
                        </div>

                        {{-- File Upload --}}
                        <div id="upload-file" class="item up-load">
                            <label class="uploadfile" for="myFile">
                                <span class="icon">
                                    <i class="icon-upload-cloud"></i>
                                </span>
                                <span class="body-text">Drop your images here or <span class="tf-color">click to browse</span></span>
                                <input type="file" id="myFile" name="image" accept="image/*" required>
                            </label>
                        </div>
                    </div>
                </fieldset>
                @error('image')
                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                @enderror

                {{-- Submit Button --}}
                <div class="bot">
                    <div></div>
                    <button class="tf-button w208" type="submit">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push("scripts")
<script>
    $(function() {
        // Show image preview when file is selected
        $("#myFile").on("change", function() {
            const [file] = this.files;
            if (file) {
                $("#imgpreview img").attr('src', URL.createObjectURL(file));
                $("#imgpreview").show();
            }
        });

        // Auto-generate slug from brand name
        $("input[name='name']").on("input", function() {
            $("input[name='slug']").val(stringToSlug($(this).val()));
        });

        function stringToSlug(text) {
            return text
                .toLowerCase()
                .replace(/[^\w\s-]/g, '')   // Remove invalid chars
                .trim()
                .replace(/\s+/g, '-')       // Replace whitespace with -
                .replace(/-+/g, '-');       // Collapse multiple -
        }
    });
</script>
@endpush
