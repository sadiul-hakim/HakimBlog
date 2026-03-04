@extends('back.layout.page-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Dashboard')
@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="title">
                    <h4>Posts</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="http://localhost:9090/admin/dashboard">Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Add New Post
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-6 col-sm-12 text-right">
                <a href="{{ route('admin.posts') }}" class="btn btn-primary">View All Posts</a>
            </div>
        </div>
    </div>
    <form action="{{ route('admin.create_post') }}" method="POST" autocomplete="off" enctype="multipart/form-data"
        id="addPostForm">
        @csrf

        <div class="row">
            <div class="col-md-9">
                <div class="card card-box mb-2">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="title"><b>Title</b>:</label>
                            <input type="text" class="form-control" name="title" id="title"
                                placeholder="Enter post title" />
                            <span class="text-danger error-text title_error"></span>
                        </div>
                        <div class="form-group">
                            <label for="content"><b>Content</b>:</label>
                            <textarea name="content" id="content" class="form-control" placeholder="Enter post content here.."></textarea>
                            <span class="text-danger error-text content_error"></span>
                        </div>
                    </div>
                </div>
                <div class="card card-box mb-2">
                    <div class="card-header weight-500">SEO</div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="meta_keywords"><b>Post meta keywords</b>: <small>(Separated by
                                    comma.)</small></label>
                            <input type="text" class="form-control" name="meta_keywords"
                                placeholder="Enter post meta keywords" />
                        </div>
                        <div class="form-group">
                            <label for="meta_description"><b>Post meta description</b>:</label>
                            <textarea name="meta_description" id="meta_description" class="form-control"
                                placeholder="Enter post meta description.."></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-box mb-2">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="category"><b>Post Category</b>:</label>
                            <select name="category" id="category" class="form-control">
                                <option value="">--Choose Category--</option>
                                @foreach ($pCategories as $pItem)
                                    <optgroup label="{{ $pItem->name }}">
                                        @foreach ($pItem->children as $pChild)
                                            <option value="{{ $pChild->id }}">{{ $pChild->name }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger error-text category_error"></span>
                        </div>
                        <div class="form-group">
                            <label for="featured_image"><b>Post Featured image</b>:</label>
                            <input type="file" name="featured_image" id="featured_image" class="form-control" />
                            <span class="text-danger error-text featured_image_error"></span>
                        </div>
                        <div class="d-block mb-3" style="max-width:250px;">
                            <img src="" alt="" class="img-thumbnail" id="featured_image_preview" />
                            <span id="preview_error" class="text-danger"></span>
                        </div>
                        <div class="form-group">
                            <label for="tags"><b>Tags</b>:</label>
                            <input type="text" class="form-control" name="tags" data-role="tagsinput" />
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="visibility"><b>Visibility</b>:</label>
                            <div class="custom-control custom-radio mb-5">
                                <input type="radio" name="visibility" id="customRadio1" class="custom-control-input"
                                    value="1" checked />
                                <label for="customRadio1" class="custom-control-label">Public</label>
                            </div>
                            <div class="custom-control custom-radio mb-5">
                                <input type="radio" name="visibility" id="customRadio2" class="custom-control-input"
                                    value="0" />
                                <label for="customRadio2" class="custom-control-label">Private</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Create Post</button>
        </div>
    </form>
@endsection
@push('stylesheets')
    <link rel="stylesheet" href="/back/src/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css" />
@endpush
@push('scripts')
    <script src="/back/src/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>
    <script>
        const input = document.getElementById('featured_image');
        const preview = document.getElementById('featured_image_preview');
        const errorSpan = document.getElementById('preview_error');

        const allowedTypes = [
            'image/png',
            'image/jpeg',
            'image/jpg',
            'image/svg+xml',
            'image/webp'
        ];

        const allowedExtensions = ['png', 'jpg', 'jpeg', 'webp', 'svg'];
        const maxSizeMB = 1; // change if needed

        input.addEventListener('change', function() {

            errorSpan.textContent = '';
            preview.style.display = 'none';

            const file = this.files[0];
            if (!file) return;

            // Check MIME type
            if (!allowedTypes.includes(file.type)) {
                errorSpan.textContent = 'Only PNG, JPG, JPEG, WEBP, SVG allowed.';
                input.value = '';
                return;
            }

            // Check extension (extra layer)
            const extension = file.name.split('.').pop().toLowerCase();
            if (!allowedExtensions.includes(extension)) {
                errorSpan.textContent = 'Invalid file extension.';
                input.value = '';
                return;
            }

            // Check file size
            if (file.size > maxSizeMB * 1024 * 1024) {
                errorSpan.textContent = `File must be under ${maxSizeMB}MB.`;
                input.value = '';
                return;
            }

            // Preview
            const objectUrl = URL.createObjectURL(file);
            preview.src = objectUrl;
            preview.style.display = 'block';

            preview.onload = () => URL.revokeObjectURL(objectUrl);
        });

        // Create a post
        $('#addPostForm').on('submit', function(e) {
            e.preventDefault();
            let form = this;
            let formData = new FormData(form);

            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: formData,
                processData: false,
                dataType: 'json',
                contentType: false,
                beforeSend: function() {
                    $(form).find('span.error-text').text('');
                },
                success: function(data) {
                    console.log(data);
                    if (data.status == 1) {
                        $(form)[0].reset();
                        $('img#featured_image_preview').attr('src', '');
                        $('input[name="tags"]').tagsinput('removeAll');
                    }
                    Swal.fire({
                        title: data.type === 'success' ? 'Success!' : 'Error!',
                        text: data.message,
                        icon: data.type,
                        timer: 2500,
                        showConfirmButton: false
                    });
                },
                error: function(data) {
                    console.log(data);
                    $.each(data.responseJSON.errors, function(prefix, val) {
                        $(form).find('span.' + prefix + '_error').text(val[0]);
                    })
                },
            });
        });
    </script>
@endpush
