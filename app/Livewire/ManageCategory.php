<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use App\Models\Category;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;

#[Layout('layouts.app')]
class ManageCategory extends Component
{
    use WithFileUploads, WithPagination, LivewireAlert;

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
        $this->pageTitle = 'Data';
        $this->componentName = 'Product Categories';
    }

    public function render()
    {
        $categories = Category::where('name', 'like', '%' . $this->searchengine . '%')
            ->orWhere('description', 'like', '%' . $this->searchengine . '%')
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
        $record = Category::find($id, ['id', 'name', 'description', 'category_photo_path']);
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
            'category_photo_path' => $imagePath
        ]);

        $this->logNotification('Category Created', "Category '{$category->name}' created.");

        $this->reset('name', 'description', 'image');
        $this->dispatch('category-added', 'Category Registered!');
        $this->alert('success', 'Category Registered Successfully!');
    }

    public function Update()
    {
        $this->validate();

        if ($this->selected_id) {
            $category = Category::find($this->selected_id);

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
            $this->alert('success', 'Category Record Updated!');
        }
    }

    public function Delete($id)
    {
        $this->selected_id = $id;
        $this->alert('warning', 'Are you sure you want to delete?', [
            'position' => 'center',
            'timer' => 6000,
            'toast' => false,
            'showConfirmButton' => true,
            'onConfirmed' => 'confirmedDeletion',
            'showCancelButton' => true,
            'timerProgressBar' => false,
            'width' => '400',
        ]);
    }

    public function confirmedDeletion()
    {
        $category = Category::find($this->selected_id);
        if ($category) {
            $category->delete();
            $this->logNotification('Category Deleted', "Category '{$category->name}' deleted.");
            $this->alert('success', 'Successfully Deleted ' . $category->name);
        } else {
            $this->alert('error', 'Unable to delete category record.');
        }
    }

    public function bulkDelete()
    {
        $categories = Category::whereIn('id', $this->selectedCategories)->get();
        $deletedNames = $categories->pluck('name')->toArray();

        Category::whereIn('id', $this->selectedCategories)->delete();

        $this->logNotification('Categories Bulk Deleted', 'Categories: ' . implode(', ', $deletedNames) . ' were deleted.');
        $this->alert('success', 'Selected categories deleted successfully.');
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
