<div class="row">
    <div class="col-12">
        <div class="pd-20 card-box mb-30">
            <div class="clearfix">
                <div class="pull-left">
                    <h4>
                        Parent Categories
                    </h4>
                </div>
                <div class="pull-right">
                    <a href="javascript:;" wire:click="addParentCategoryModal()" class="btn btn-primary btn-sm">Add P.
                        Category</a>
                </div>
            </div>
            <div class="table-responsive mt-4">
                <table class="table table-borderless table-striped table-sm">
                    <thead class="bg-secondary text-white">
                        <th>#</th>
                        <th>Name</th>
                        <th>N. of Categories</th>
                        <th>Action</th>
                    </thead>
                    <tbody id="sortable_parent_categories">
                        @forelse ($pCategories as $pCategory)
                            <tr data-index="{{ $pCategory->id }}" data-ordering="{{ $pCategory->ordering }}">
                                <td>
                                    {{ $pCategory->id }}
                                </td>
                                <td>
                                    {{ $pCategory->name }}
                                </td>
                                <td>
                                    -
                                </td>
                                <td>
                                    <div class="table-actions">
                                        <a href="javascript:;"
                                            wire:click="editParentCategoryModal({{ $pCategory->id }})"
                                            class="text-primary mx-2">
                                            <i class="dw dw-edit2"></i>
                                        </a>
                                        <a href="javascript:;" wire:click="deleteParentCategory({{ $pCategory->id }})"
                                            wire:confirm="Are you sure you want to delete this parent category?"
                                            class="text-danger mx-2">
                                            <i class="dw dw-delete-3"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-danger">
                                    No Items found!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{-- Parent Category Ends --}}
    <div class="col-12">
        <div class="pd-20 card-box mb-30">
            <div class="clearfix">
                <div class="pull-left">
                    <h4>
                        Categories
                    </h4>
                </div>
                <div class="pull-right">
                    <a href="javascript:;" wire:click="addCategoryModal()" class="btn btn-primary btn-sm">Add
                        Category</a>
                </div>
            </div>
            <div class="table-responsive mt-4">
                <table class="table table-borderless table-striped table-sm">
                    <thead class="bg-secondary text-white">
                        <th>#</th>
                        <th>Name</th>
                        <th>Parent Categories</th>
                        <th>N. of Posts</th>
                        <th>Action</th>
                    </thead>
                    <tbody id="sortable_parent_categories">
                        @forelse ($categories as $category)
                            <tr data-index="{{ $category->id }}" data-ordering="{{ $category->ordering }}">
                                <td>
                                    {{ $category->id }}
                                </td>
                                <td>
                                    {{ $category->name }}
                                </td>
                                <td>
                                    {{ $category->parent }}
                                </td>
                                <td>
                                    -
                                </td>
                                <td>
                                    <div class="table-actions">
                                        <a href="javascript:;" wire:click="editCategoryModal({{ $category->id }})"
                                            class="text-primary mx-2">
                                            <i class="dw dw-edit2"></i>
                                        </a>
                                        <a href="javascript:;" wire:click="deleteCategory({{ $category->id }})"
                                            wire:confirm="Are you sure you want to delete this category?"
                                            class="text-danger mx-2">
                                            <i class="dw dw-delete-3"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-danger">
                                    No Items found!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Area --}}
    <div class="modal fade" wire:ignore.self id="parentCategoryModal" tabindex="-1"
        aria-labelledby="parentCategoryModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <form class="modal-content"
                wire:submit="{{ $isUpdateParentCategoryMood ? 'updateParentCategory()' : 'createParentCategory()' }}">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="parentCategoryModalLabel">
                        {{ $isUpdateParentCategoryMood ? 'Update P. Category' : 'Add P. Category' }}
                    </h1>
                </div>
                <div class="modal-body">
                    @if ($isUpdateParentCategoryMood)
                        <input type="hidden" wire:model="pCategory_id" />
                    @endif
                    <div class="form-group">
                        <label for="pCategory_name"><b>Parent Category Name</b></label>
                        <input type="text" class="form-control" name="pCategory_name" id="pCategory_name"
                            wire:model="pCategory_name" placeholder="Enter parent category name" />
                        @error('pCategory_name')
                            <span class="text-danger ml-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">
                        {{ $isUpdateParentCategoryMood ? 'Save Changes' : 'Create' }}
                    </button>
                    <button type="button" class="btn btn-secondary"
                        wire:click="hideParentCategoryModalForm()">Close</button>
                </div>
            </form>
        </div>
    </div>
    {{-- Parent Category Modal Ends --}}
    <div class="modal fade" wire:ignore.self id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel"
        aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <form class="modal-content"
                wire:submit="{{ $isUpdateCategoryMood ? 'updateCategory()' : 'createCategory()' }}">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="categoryModalLabel">
                        {{ $isUpdateCategoryMood ? 'Update Category' : 'Add Category' }}
                    </h1>
                </div>
                <div class="modal-body">
                    @if ($isUpdateCategoryMood)
                        <input type="hidden" wire:model="category_id" />
                    @endif
                    <div class="form-group">
                        <label for="category_name"><b>Category Name</b></label>
                        <input type="text" class="form-control" name="category_name" id="category_name"
                            wire:model="category_name" placeholder="Enter category name" />
                        @error('category_name')
                            <span class="text-danger ml-1">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="category_parent"><b>Parent</b></label>
                        <select name="category_parent" id="category_parent" wire:model="category_parent"
                            class="form-control">
                            <option value="">--Select Parent Category--</option>
                            @foreach ($pCategories as $parent)
                                <option value="{{ $parent->id }}">
                                    {{ $parent->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">
                        {{ $isUpdateCategoryMood ? 'Save Changes' : 'Create' }}
                    </button>
                    <button type="button" class="btn btn-secondary"
                        wire:click="hideCategoryModalForm()">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
