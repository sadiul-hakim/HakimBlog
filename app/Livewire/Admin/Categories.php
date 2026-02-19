<?php

namespace App\Livewire\Admin;

use App\Models\ParentCategory;
use Livewire\Component;

class Categories extends Component
{
    public bool $isUpdateParentCategoryMood = false;
    public $pCategory_id, $pCategory_name;

    public function render()
    {
        return view('livewire.admin.categories', [
            'pCategories' => ParentCategory::orderBy('ordering', 'asc')->get()
        ]);
    }

    public function addParentCategoryModal()
    {
        $this->pCategory_id = null;
        $this->pCategory_name = null;
        $this->isUpdateParentCategoryMood = false;
        $this->showParentCategoryModalForm();
    }

    public function updateParentCategoryModal()
    {
        $this->pCategory_id = null;
        $this->pCategory_name = null;
        $this->isUpdateParentCategoryMood = true;
        $this->showParentCategoryModalForm();
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


    public function updateParentCategory() {}
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
}
