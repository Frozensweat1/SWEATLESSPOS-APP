<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use App\Models\Store;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

#[Layout('layouts.app')]
class ManageStore extends Component
{
    use WithFileUploads, WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $name, $address, $phone, $email, $logo_path, $initial_balance, $is_active = true;
    public $componentName = 'Stores', $pageTitle = 'Data', $isEditMode = false;
    public $currentLogo, $searchengine = '', $selected_id;
    public $selectedStores = [];
    public $selectAll = false;
    public $perPage = 10;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:stores,name,' . $this->selected_id,
            'address' => 'required|string|max:500',
            'phone' => 'required|string|max:20|unique:stores,phone,' . $this->selected_id,
            'email' => 'required|email|max:255|unique:stores,email,' . $this->selected_id,
            'logo_path' => 'nullable|image|max:2048',
            'initial_balance' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ];
    }

    public function mount()
    {
        $this->pageTitle = 'Manage';
        $this->componentName = 'Stores';
    }

    public function render()
    {
        $stores = Store::where('name', 'like', '%' . $this->searchengine . '%')
            ->orWhere('email', 'like', '%' . $this->searchengine . '%')
            ->orWhere('phone', 'like', '%' . $this->searchengine . '%')
            ->paginate($this->perPage);

        return view('livewire.manage-store', ['stores' => $stores]);
    }

    public function create()
    {
        $this->reset('name', 'address', 'phone', 'email', 'logo_path', 'initial_balance', 'is_active');
        $this->selected_id = null;
        $this->currentLogo = null;
        $this->is_active = true;
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
        $this->reset('name', 'address', 'phone', 'email', 'logo_path', 'initial_balance');
        $this->resetUI();
    }

    public function Edit($id)
    {
        $this->resetUI();
        $this->isEditMode = true;
        $record = Store::find($id);

        if (!$record) {
            LivewireAlert::title('Error')
                ->text('Store not found.')
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }

        $this->selected_id = $record->id;
        $this->name = $record->name;
        $this->address = $record->address;
        $this->phone = $record->phone;
        $this->email = $record->email;
        $this->currentLogo = $record->logo_path;
        $this->initial_balance = $record->initial_balance;
        $this->is_active = $record->is_active;
        $this->openModal();
    }

    public function Store()
    {
        $this->validate();

        $logoPath = $this->logo_path ? $this->logo_path->store('store-logos', 'public') : null;

        $store = Store::create([
            'name' => $this->name,
            'address' => $this->address,
            'phone' => $this->phone,
            'email' => $this->email,
            'logo_path' => $logoPath,
            'initial_balance' => $this->initial_balance,
            'is_active' => $this->is_active,
        ]);

        $this->logNotification('Store Created', "Store '{$store->name}' has been created successfully.");

        $this->reset('name', 'address', 'phone', 'email', 'logo_path', 'initial_balance');
        $this->closeModal();

        LivewireAlert::title('Success')
            ->text('Store Created Successfully!')
            ->success()
            ->toast()
            ->position('top-end')
            ->show();
    }

    public function Update()
    {
        $this->validate();

        if ($this->selected_id) {
            $store = Store::find($this->selected_id);

            if (!$store) {
                LivewireAlert::title('Error')
                    ->text('Store not found.')
                    ->error()
                    ->toast()
                    ->position('top-end')
                    ->show();
                return;
            }

            $data = [
                'name' => $this->name,
                'address' => $this->address,
                'phone' => $this->phone,
                'email' => $this->email,
                'initial_balance' => $this->initial_balance,
                'is_active' => $this->is_active,
            ];

            if ($this->logo_path) {
                $data['logo_path'] = $this->logo_path->store('store-logos', 'public');
            }

            $store->update($data);

            $this->logNotification('Store Updated', "Store '{$store->name}' has been updated successfully.");

            $this->resetUI();
            $this->closeModal();

            LivewireAlert::title('Success')
                ->text('Store Updated Successfully!')
                ->success()
                ->toast()
                ->position('top-end')
                ->show();
        }
    }

    public function Delete($id)
    {
        $this->selected_id = $id;
        LivewireAlert::title('Are you sure you want to delete this store?')
            ->html('This action cannot be undone.')
            ->warning()
            ->onConfirm('confirmedDeletion')
            ->withConfirmButton('Yes, Delete')
            ->withCancelButton('Cancel')
            ->show();
    }

    public function confirmedDeletion()
    {
        $store = Store::find($this->selected_id);

        if (!$store) {
            LivewireAlert::title('Error')
                ->text('Store not found.')
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }

        // Prevent deletion of default store (ID 1)
        if ($store->id === 1) {
            LivewireAlert::title('Error')
                ->text('Cannot delete the default store.')
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }

        $storeName = $store->name;
        $store->delete();

        $this->logNotification('Store Deleted', "Store '{$storeName}' has been deleted.");

        LivewireAlert::title('Success')
            ->text('Store Deleted Successfully!')
            ->success()
            ->toast()
            ->position('top-end')
            ->show();
    }

    public function bulkDelete()
    {
        if (count($this->selectedStores) === 0) {
            LivewireAlert::title('Warning')
                ->text('No stores selected.')
                ->warning()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }

        // Check if store 1 is in selection
        if (in_array(1, $this->selectedStores)) {
            LivewireAlert::title('Error')
                ->text('Cannot delete the default store.')
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }

        LivewireAlert::title('Are you sure?')
            ->html('You are about to delete selected stores. This action cannot be undone.')
            ->warning()
            ->onConfirm('confirmedBulkDeletion')
            ->withConfirmButton('Yes, Delete')
            ->withCancelButton('Cancel')
            ->show();
    }

    public function confirmedBulkDeletion()
    {
        $stores = Store::whereIn('id', $this->selectedStores)->get();
        $deletedNames = $stores->pluck('name')->toArray();

        Store::whereIn('id', $this->selectedStores)->delete();

        $this->logNotification('Bulk Delete Stores', 'Stores deleted: ' . implode(', ', $deletedNames));

        $this->selectedStores = [];
        $this->selectAll = false;

        LivewireAlert::title('Success')
            ->text('Selected stores deleted successfully.')
            ->success()
            ->toast()
            ->position('top-end')
            ->show();
    }

    public function updatedSelectAll($value)
    {
        $this->selectedStores = $value ? $this->getStoresProperty()->pluck('id')->toArray() : [];
    }

    public function updatedSelectedStores()
    {
        $this->selectAll = count($this->selectedStores) === count($this->getStoresProperty()->pluck('id')->toArray());
    }

    public function getStoresProperty()
    {
        return Store::where('name', 'like', '%' . $this->searchengine . '%')
            ->orWhere('email', 'like', '%' . $this->searchengine . '%')
            ->orWhere('phone', 'like', '%' . $this->searchengine . '%')
            ->get();
    }

    protected $listeners = ['confirmedDeletion', 'confirmedBulkDeletion'];

    protected function logNotification($title, $message, $type = 'info')
    {
        Notification::create([
            'user_id' => Auth::id(),
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'is_read' => false,
            'read_at' => null,
        ]);
    }

    public function resetUI()
    {
        $this->name = '';
        $this->address = '';
        $this->phone = '';
        $this->email = '';
        $this->logo_path = null;
        $this->initial_balance = 0;
        $this->is_active = true;
        $this->currentLogo = null;
    }
}
