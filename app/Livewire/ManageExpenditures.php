<?php

namespace App\Livewire;

use App\Models\Expenditure;
use App\Models\Notification;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use App\Traits\StoreScoped;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Illuminate\Support\Facades\Auth;


#[Layout('layouts.app')]
class ManageExpenditures extends Component
{
    use WithFileUploads;
    use WithPagination;
    use StoreScoped;

    protected $paginationTheme = 'bootstrap'; // Using Bootstrap for pagination UI

    public $categoryFilter = '';
    public $dateFilter = '';
    public $editExpenditure = null;

    public $description, $amount, $payment_method, $category, $expenditure_date, $supplier_id, $notes;
    public $componentName;
    public $pageTitle;
    public $searchengine;
    public $isEditMode = false;
    public $selected_id;

    protected function rules()
    {
        return [
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,credit_card,mobile_money,bank_transfer',
            'category' => 'nullable|string|max:255',
            'expenditure_date' => 'required|date',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'notes' => 'nullable|string',
        ];
    }

    public function mount()
    {
        $this->initStoreId();
        $this->pageTitle = 'Manage Expenses';
        $this->componentName = 'Expenditure';
    }

    public function render()
    {
        $query = Expenditure::where('store_id', $this->getStoreId())
            ->where(function ($q) {
                if ($this->searchengine) {
                    $q->where('description', 'like', '%' . $this->searchengine . '%')
                        ->orWhere('category', 'like', '%' . $this->searchengine . '%');
                }
                if ($this->categoryFilter) {
                    $q->where('category', $this->categoryFilter);
                }
                if ($this->dateFilter) {
                    $q->whereDate('expenditure_date', $this->dateFilter);
                }
            });

        return view('livewire.manage-expenditures', [
            'expenditures' => $query->paginate(10),
            'categories' => Expenditure::where('store_id', $this->getStoreId())->select('category')->distinct()->pluck('category')
        ]);
    }

    public function create()
    {
        $this->reset('description', 'amount', 'payment_method', 'category', 'expenditure_date', 'supplier_id', 'notes');
        $this->payment_method = 'cash';
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
        $this->reset('description', 'amount', 'payment_method', 'category', 'expenditure_date', 'supplier_id', 'notes');
        $this->resetUI();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->searchengine = '';
        $this->categoryFilter = '';
        $this->dateFilter = '';
        $this->resetPage();
    }

    public function Edit($id)
    {
        $this->resetUI();
        $record = Expenditure::where('store_id', $this->getStoreId())
            ->find($id, ['id', 'description', 'amount', 'payment_method', 'category', 'expenditure_date', 'supplier_id', 'notes']);
        $this->selected_id = $record->id;
        $this->description = $record->description;
        $this->amount = $record->amount;
        $this->payment_method = $record->payment_method;
        $this->category = $record->category;
        $this->expenditure_date = $record->expenditure_date;
        $this->supplier_id = $record->supplier_id;
        $this->notes = $record->notes;
        $this->openModal();
    }

    public function Store()
    {
        $this->validate($this->rules());

        // Create the expenditure record
        Expenditure::create([
            'description' => $this->description,
            'amount' => $this->amount,
            'payment_method' => $this->payment_method,
            'category' => $this->category,
            'expenditure_date' => $this->expenditure_date,
            'user_id' => Auth::id(),
            'supplier_id' => $this->supplier_id,
            'notes' => $this->notes,
            'store_id' => $this->getStoreId(),
        ]);

        // Log the notification
        $this->logNotification('Expenditure Added', 'A new expenditure record was created.', 'success');

        // Reset form fields after saving
        $this->reset(['description', 'amount', 'payment_method', 'category', 'expenditure_date', 'supplier_id', 'notes']);

        $this->dispatch('expenditure-added', 'Expenditure Added!');
        LivewireAlert::title('Success')
            ->text('Expenditure Added Successfully!')
            ->success()
            ->toast()
            ->position('top-end')
            ->show();
    }

    public function Update()
    {
        $this->validate($this->rules());

        if ($this->selected_id) {
            $expenditure = Expenditure::where('store_id', $this->getStoreId())->find($this->selected_id);
            $expenditure->update([
                'description' => $this->description,
                'amount' => $this->amount,
                'payment_method' => $this->payment_method,
                'category' => $this->category,
                'expenditure_date' => $this->expenditure_date,
                'supplier_id' => $this->supplier_id,
                'notes' => $this->notes,
            ]);

            // Log the notification
            $this->logNotification('Expenditure Updated', 'An expenditure record was updated.', 'info');

            $this->resetUI();
            $this->dispatch('expenditure-updated', 'Expenditure Record Updated!');
            LivewireAlert::title('Success')
                ->text('Expenditure Record Updated!')
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
        $expense = Expenditure::where('store_id', $this->getStoreId())->find($this->selected_id);
        if ($expense) {
            $expense->delete();

            // Log the notification
            $this->logNotification('Expenditure Deleted', 'An expenditure record was deleted', 'warning');

            LivewireAlert::title('Deleted')
                ->text('Successfully Deleted Expenditure record')
                ->success()
                ->toast()
                ->position('top-end')
                ->show();
        } else {
            LivewireAlert::title('Error')
                ->text('Unable to delete Expenditure record.')
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
        }
    }

    protected $listeners = [
        'confirmedDeletion'
    ];

    protected function logNotification($title, $message, $type)
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
        $this->description = '';
        $this->amount = '';
        $this->payment_method = 'cash';
        $this->category = '';
        $this->expenditure_date = '';
        $this->supplier_id = '';
        $this->notes = '';
        $this->categoryFilter = '';
        $this->dateFilter = '';
        $this->searchengine = '';
        $this->isEditMode = false;
        $this->selected_id = 0;
        $this->resetPage();
    }
}
