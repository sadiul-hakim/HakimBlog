<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\ParentCategory;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Throwable;

class Categories extends Component
{
    use WithPagination;

    public bool $isUpdateParentCategoryMood = false;
    public bool $isUpdateCategoryMood = false;
    public $pCategory_id, $pCategory_name;
    public $category_id, $category_name, $category_parent;

    public $pCategoriesPerPage = 5;
    public $categoriesPerPage = 10;

    protected $listeners = ['updateParentCategoryOrdering', 'updateCategoryOrdering'];

    public function render()
    {
        return view('livewire.admin.categories', [
            'pCategories' => ParentCategory::with("children")->orderBy('ordering', 'asc')
                ->paginate($this->pCategoriesPerPage, ["*"], "pCat_page"),
            'categories' => Category::with("parent_category")->orderBy('ordering', 'asc')
                ->paginate($this->categoriesPerPage, ["*"], "cat_page"),
        ]);
    }

    public function addParentCategoryModal()
    {
        $this->pCategory_id = null;
        $this->pCategory_name = null;
        $this->isUpdateParentCategoryMood = false;
        $this->showParentCategoryModalForm();
    }

    public function addCategoryModal()
    {
        $this->category_id = null;
        $this->category_name = null;
        $this->isUpdateCategoryMood = false;
        $this->showCategoryModalForm();
    }

    public function deleteParentCategory(int $id)
    {
        try {

            // In PHP, anonymous functions do NOT automatically inherit variables from the outer scope.
            // use () says, Import this external variable into the closure.
            DB::transaction(function () use ($id) {
                $pCategory = ParentCategory::findOrFail($id);

                if ($pCategory->children->count() > 0) {
                    foreach ($pCategory->children as $category) {

                        // Release the category
                        Category::where("id", $category->id)->update(['parent' => 0]);
                    }
                }

                $pCategory->delete();
            });

            $this->hideParentCategoryModalForm();
            $this->dispatch('showAlert', ['type' => 'success', 'message' => 'Parent Category has been deleted successfully.']);
        } catch (Throwable $ex) {

            $this->dispatch('showAlert', ['type' => 'error', 'message' => 'Something went wrong!']);
        }
    }

    public function deleteCategory(int $id)
    {
        $category = Category::findOrFail($id);

        // Do some checking
        $deleted = $category->delete();

        if ($deleted) {
            $this->hideParentCategoryModalForm();
            $this->dispatch('showAlert', ['type' => 'success', 'message' => 'Category has been deleted successfully.']);
        } else {
            $this->dispatch('showAlert', ['type' => 'error', 'message' => 'Something went wrong!']);
        }
    }

    public function editParentCategoryModal(int $id)
    {
        $pCategory = ParentCategory::find($id);
        $this->pCategory_id = $pCategory->id;
        $this->pCategory_name = $pCategory->name;
        $this->isUpdateParentCategoryMood = true;
        $this->showParentCategoryModalForm();
    }

    public function editCategoryModal(int $id)
    {
        $category = Category::find($id);
        $this->category_id = $category->id;
        $this->category_name = $category->name;
        $this->category_parent = $category->parent;
        $this->isUpdateCategoryMood = true;
        $this->showCategoryModalForm();
    }

    private function showParentCategoryModalForm()
    {
        $this->resetErrorBag();
        $this->dispatch('showParentCategoryModalForm');
    }

    public function hideParentCategoryModalForm()
    {
        $this->dispatch('hideParentCategoryModalForm');
        $this->isUpdateParentCategoryMood = false;
        $this->pCategory_id = $this->pCategory_name = null;
    }

    private function showCategoryModalForm()
    {
        $this->resetErrorBag();
        $this->dispatch('showCategoryModalForm');
    }

    public function hideCategoryModalForm()
    {
        $this->dispatch('hideCategoryModalForm');
        $this->isUpdateCategoryMood = false;
        $this->category_id = $this->category_name = $this->category_parent = null;
    }

    public function updateParentCategory()
    {
        $pCategory = ParentCategory::findOrFail($this->pCategory_id);
        $this->validate([
            'pCategory_name' => 'required|unique:parent_categories,name,' . $pCategory->id
        ], [
            'pCategory_name.required' => 'Parent Category name field is required',
            'pCategory_name.unique' => 'This Parent Category name is taken',
        ]);

        $pCategory->name = $this->pCategory_name;
        $pCategory->slug = null;
        $updated = $pCategory->save();
        if ($updated) {
            $this->hideParentCategoryModalForm();
            $this->dispatch('showAlert', ['type' => 'success', 'message' => 'Parent Category has been updated successfully.']);
        } else {
            $this->dispatch('showAlert', ['type' => 'error', 'message' => 'Something went wrong!']);
        }
    }
    public function createParentCategory()
    {
        $this->validate([
            'pCategory_name' => 'required|unique:parent_categories,name'
        ], [
            'pCategory_name.required' => 'Parent Category name field is required',
            'pCategory_name.unique' => 'This Parent Category name already exists',
        ]);

        $pCategory = new ParentCategory();
        $pCategory->name = $this->pCategory_name;
        $saved = $pCategory->save();

        if ($saved) {
            $this->hideParentCategoryModalForm();
            $this->dispatch('showAlert', ['type' => 'success', 'message' => 'Parent Category is saved successfully.']);
        } else {
            $this->dispatch('showAlert', ['type' => 'error', 'message' => 'Something went wrong!']);
        }
    }

    public function updateParentCategoryOrdering($positions)
    {
        foreach ($positions as $position) {
            $id = $position[0];
            $ordering = $position[1];
            ParentCategory::where('id', $id)->update(['ordering' => $ordering]);
        }
        $this->dispatch('showAlert', ['type' => 'success', 'message' => 'Parent Category order has been updated successfully.']);
    }

    public function updateCategoryOrdering($positions)
    {
        foreach ($positions as $position) {
            $id = $position[0];
            $ordering = $position[1];
            Category::where('id', $id)->update(['ordering' => $ordering]);
        }
        $this->dispatch('showAlert', ['type' => 'success', 'message' => 'Category order has been updated successfully.']);
    }

    public function createCategory()
    {
        $this->validate([
            'category_name' => 'required|unique:categories,name'
        ], [
            'category_name.required' => 'Category name field is required',
            'category_name.unique' => 'This Category name already exists',
        ]);

        $category = new Category();
        $category->name = $this->category_name;
        $category->parent = $this->category_parent ?? 0;
        $saved = $category->save();

        if ($saved) {
            $this->hideCategoryModalForm();
            $this->dispatch('showAlert', ['type' => 'success', 'message' => 'Category is saved successfully.']);
        } else {
            $this->dispatch('showAlert', ['type' => 'error', 'message' => 'Something went wrong!']);
        }
    }

    public function updateCategory()
    {
        $category = Category::findOrFail($this->category_id);
        $this->validate([
            'category_name' => 'required|unique:categories,name,' . $category->id
        ], [
            'category_name.required' => 'Category name field is required',
            'category_name.unique' => 'This Category name is taken',
        ]);

        $category->name = $this->category_name;
        $category->parent = $this->category_parent;
        $category->slug = null;
        $updated = $category->save();
        if ($updated) {
            $this->hideCategoryModalForm();
            $this->dispatch('showAlert', ['type' => 'success', 'message' => 'Category has been updated successfully.']);
        } else {
            $this->dispatch('showAlert', ['type' => 'error', 'message' => 'Something went wrong!']);
        }
    }
}
