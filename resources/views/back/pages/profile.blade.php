@extends('back.layout.page-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Profile')
@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="title">
                    <h4>Profile</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Profile
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    {{-- Page Header Ends --}}
    {{-- <livewire:admin.profile /> --}}
    @livewire('admin.profile')
@endsection
@push('scripts')
    <script>
        const cropper = new Kropify('#profilePictureFile', {
            aspectRatio: 1,
            viewMode: 1,
            preview: 'img#profilePicturePreview',
            processURL: '{{ route('admin.update_profile_picture') }}',
            maxSize: 2 * 1024 * 1024, // 2MB
            allowedExtensions: ['jpg', 'jpeg', 'png', 'svg'],
            showLoader: true,
            animationClass: 'pulse',
            // fileName: 'avatar', // leave this commented if you want it to default to the input name
            cancelButtonText: 'Cancel',
            resetButtonText: 'Reset',
            cropButtonText: 'Crop & Upload',
            maxWoH: 500,
            onError: function(msg) {

                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to update Profile Picture',
                    icon: 'error',
                    timer: 2500,
                    showConfirmButton: false
                });
            },
            onDone: function(response) {
                if (response.status == 1) {
                    Livewire.dispatch('updateTopUserInfo', []);
                    Livewire.dispatch('updateProfile', []);
                    Swal.fire({
                        title: 'Success!',
                        text: response.message,
                        icon: 'success',
                        timer: 2500,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: response.message,
                        icon: 'error',
                        timer: 2500,
                        showConfirmButton: false
                    });
                }
            }
        });
    </script>
@endpush
