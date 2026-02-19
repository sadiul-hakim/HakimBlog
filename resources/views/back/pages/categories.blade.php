@extends('back.layout.page-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Dashboard')
@section('content')
    @livewire('admin.categories')
@endsection
@push('scripts')
    <script>
        window.addEventListener('showParentCategoryModalForm', function() {
            $('#parentCategoryModal').modal('show');
        })

        window.addEventListener('hideParentCategoryModalForm', function() {
            $('#parentCategoryModal').modal('hide');
        })
    </script>
@endpush
