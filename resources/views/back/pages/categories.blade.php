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

        window.addEventListener('showCategoryModalForm', function() {
            $('#categoryModal').modal('show');
        })

        window.addEventListener('hideCategoryModalForm', function() {
            $('#categoryModal').modal('hide');
        })

        $('table tbody#sortable_parent_categories').sortable({
            cursor: "move",
            update: function(event, ui) {
                $(this).children().each(function(index) {
                    if ($(this).attr('data-ordering') != index + 1) {
                        $(this).attr('data-ordering', (index + 1)).addClass('updated');
                    }
                });
                let positions = [];
                $('.updated').each(function() {
                    positions.push([$(this).attr('data-index'), $(this).attr('data-ordering')]);
                    $(this).removeClass('updated');
                });
                Livewire.dispatch('updateParentCategoryOrdering', [positions]);
            }
        });

        $('table tbody#sortable_categories').sortable({
            cursor: "move",
            update: function(event, ui) {
                $(this).children().each(function(index) {
                    if ($(this).attr('data-ordering') != index + 1) {
                        $(this).attr('data-ordering', (index + 1)).addClass('updated');
                    }
                });
                let positions = [];
                $('.updated').each(function() {
                    positions.push([$(this).attr('data-index'), $(this).attr('data-ordering')]);
                    $(this).removeClass('updated');
                });
                Livewire.dispatch('updateCategoryOrdering', [positions]);
            }
        })
    </script>
@endpush
