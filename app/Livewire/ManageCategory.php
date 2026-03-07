<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use App\Models\Category;
use App\Models\Notification;
use App\Traits\StoreScoped;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

#[Layout('layouts.app')]
class ManageCategory extends Component
{
    use WithFileUploads, WithPagination, StoreScoped;

    protected $paginationTheme = 'bootstrap'; // Using Bootstrap for pagination UI

    public $name, $description, $image, $categoryId;
    public $componentName, $currentImage, $searchengine, $pageTitle, $isEditMode = false;
    public $path, $selected_id;
    public $selectedCategories = []; // Array for bulk delete selection

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:categories,name,' . $this->selected_id,
            'description' => 'required|string|max:1020',
            'image' => 'nullable|image|max:5024', // max 5MB
        ];
    }

    public function mount()
    {
        $this->initStoreId();
        $this->pageTitle = 'Data';
        $this->componentName = 'Product Categories';
    }

    public function render()
    {
        $categories = Category::where('store_id', $this->getStoreId())
            ->where(function ($q) {
                $q->where('name', 'like', '%' . $this->searchengine . '%')
                    ->orWhere('description', 'like', '%' . $this->searchengine . '%');
            })
            ->paginate(10);

        return view('livewire.manage-category', ['categories' => $categories]);
    }

    public function create()
    {
        $this->reset('name', 'description', 'image');
        $this->resetUI();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isEditMode = true;
    }

    public function closeModal()
    {
        $this->isEditMode = false;
        $this->reset('name', 'description', 'image');
        $this->resetUI();
    }

    public function Edit($id)
    {
        $this->resetUI();
        $this->isEditMode = true;
        $record = Category::where('store_id', $this->getStoreId())->find($id, ['id', 'name', 'description', 'category_photo_path']);
        $this->selected_id = $record->id;
        $this->name = $record->name;
        $this->description = $record->description;
        $this->currentImage = $record->category_photo_path;
        $this->openModal();
    }

    public function Store()
    {
        $this->validate();

        $imagePath = $this->image ? $this->image->store('categories') : null;

        $category = Category::create([
            'name' => $this->name,
            'description' => $this->description,
            'category_photo_path' => $imagePath,
            'store_id' => $this->getStoreId(),
        ]);

        $this->logNotification('Category Created', "Category '{$category->name}' created.");

        $this->reset('name', 'description', 'image');
        $this->dispatch('category-added', 'Category Registered!');
        LivewireAlert::title('Category Saved')
            ->text('The Category has been successfully saved to the database.')
            ->success()
            ->toast()
            ->position('top-end')
            ->show();
    }

    public function Update()
    {
        $this->validate();

        if ($this->selected_id) {
            $category = Category::where('store_id', $this->getStoreId())->find($this->selected_id);

            $data = [
                'name' => $this->name,
                'description' => $this->description,
            ];

            if ($this->image) {
                $data['category_photo_path'] = $this->image->store('categories');
            }

            $category->update($data);
            $this->logNotification('Category Updated', "Category '{$category->name}' updated.");

            $this->resetUI();
            $this->dispatch('category-updated', 'Category Record Updated!');
            LivewireAlert::title('Success')
                ->text('Category Record Updated!')
                ->success()
                ->toast()
                ->position('top-end')
                ->show();
        }
    }

    public function Delete($id)
    {
        $this->selected_id = $id;
        LivewireAlert::title('Are you sure you want to delete?')
            ->warning()
            ->onConfirm('confirmedDeletion')
            ->withConfirmButton('Yes, Delete')
            ->withCancelButton('Cancel')
            ->show();
    }

    public function confirmedDeletion()
    {
        $category = Category::where('store_id', $this->getStoreId())->find($this->selected_id);
        if ($category) {
            $category->delete();
            $this->logNotification('Category Deleted', "Category '{$category->name}' deleted.");
            LivewireAlert::title('Deleted')
                ->text('Successfully Deleted ' . $category->name)
                ->success()
                ->toast()
                ->position('top-end')
                ->show();
        } else {
            LivewireAlert::title('Error')
                ->text('Unable to delete category record.')
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
        }
    }

    public function bulkDelete()
    {
        $categories = Category::whereIn('id', $this->selectedCategories)->get();
        $deletedNames = $categories->pluck('name')->toArray();

        Category::where('store_id', $this->getStoreId())->whereIn('id', $this->selectedCategories)->delete();

        $this->logNotification('Categories Bulk Deleted', 'Categories: ' . implode(', ', $deletedNames) . ' were deleted.');
        LivewireAlert::title('Success')
            ->text('Selected categories deleted successfully.')
            ->success()
            ->toast()
            ->position('top-end')
            ->show();
        $this->reset('selectedCategories');
    }

    protected function logNotification($title, $message)
    {
        $authUser = Auth::user();

        Notification::create([
            'user_id' => $authUser->id,
            'title' => $title,
            'message' => $message . " Action by {$authUser->name} (ID: {$authUser->id}).",
            'type' => 'info',
            'is_read' => false,
        ]);
    }

    protected $listeners = ['confirmedDeletion'];

    public function resetUI()
    {
        $this->name = '';
        $this->description = '';
        $this->image = null;
        $this->categoryId = null;
        $this->currentImage = null;
        $this->selected_id = 0;
        $this->selectedCategories = [];
    }
}
