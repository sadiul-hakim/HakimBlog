@extends('back.layout.page-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'General Setting')
@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="title">
                    <h4>Settings</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <a href="{{ route('admin.settings') }}">General Setting</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    {{-- Page Header Ends --}}
    @livewire('admin.settings')
    {{-- Tabs End Here --}}
@endsection
@push('scripts')
    <script>
        const input = document.getElementById('site_logo');
        const preview = document.getElementById('preview_site_logo');
        const errorSpan = document.getElementById('logo_error');

        const allowedTypes = [
            'image/png',
            'image/jpeg',
            'image/jpg',
            'image/svg+xml',
            'image/webp'
        ];

        const allowedExtensions = ['png', 'jpg', 'jpeg', 'webp', 'svg'];
        const maxSizeMB = 2; // change if needed

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

        // Send Request to update logo
        $("#updateLogoForm").submit(function(e) {
            e.preventDefault();
            let form = this;
            let input = $(form).find('input[type="file"]').val();
            let errorElement = $(form).find('span#logo_error');
            errorElement.text('');
            if (input.length > 0) {
                $.ajax({
                    url: $(form).attr('action'),
                    method: $(form).attr('method'),
                    data: new FormData(form),
                    processData: false,
                    dataType: 'json',
                    contentType: false,
                    beforeSend: function() {},
                    success: function(data) {
                        if (data.status == 1) {
                            $(form)[0].reset();

                            Swal.fire({
                                title: 'Success!',
                                text: data.message,
                                icon: 'success',
                                timer: 2500,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: data.message,
                                icon: 'error',
                                timer: 2500,
                                showConfirmButton: false
                            });
                        }
                    }
                })
            } else {
                errorElement.text('Please select an image file.');
            }
        });

        // Send Request to update favicon
        $("#updateFaviconForm").submit(function(e) {
            e.preventDefault();
            let form = this;
            let input = $(form).find('input[type="file"]').val();
            let errorElement = $(form).find('span#favicon_error');
            errorElement.text('');
            if (input.length > 0) {
                $.ajax({
                    url: $(form).attr('action'),
                    method: $(form).attr('method'),
                    data: new FormData(form),
                    processData: false,
                    dataType: 'json',
                    contentType: false,
                    beforeSend: function() {},
                    success: function(data) {
                        if (data.status == 1) {
                            $(form)[0].reset();

                            Swal.fire({
                                title: 'Success!',
                                text: data.message,
                                icon: 'success',
                                timer: 2500,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: data.message,
                                icon: 'error',
                                timer: 2500,
                                showConfirmButton: false
                            });
                        }
                    }
                })
            } else {
                errorElement.text('Please select an image file.');
            }
        });
    </script>
@endpush
