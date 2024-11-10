<?php

namespace App\Livewire;

use App\Models\Expenditure;
use App\Models\Notification; // Import the Notification model
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\Auth;


#[Layout('layouts.app')]
class ManageExpenditures extends Component
{
    use WithFileUploads;
    use WithPagination;
    use LivewireAlert;

    protected $paginationTheme = 'bootstrap'; // Using Bootstrap for pagination UI

    public $categoryFilter = '';
    public $dateFilter = '';
    public $editExpenditure = null;

    public $description, $amount, $category, $expenditure_date, $supplier_id, $notes;
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
            'category' => 'nullable|string|max:255',
            'expenditure_date' => 'required|date',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'notes' => 'nullable|string',
        ];
    }

    public function mount()
    {
        $this->pageTitle = 'Manage Expenses';
        $this->componentName = 'Expenditure';
    }

    public function render()
    {
        $query = Expenditure::where('user_id', Auth::id())
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
            'categories' => Expenditure::select('category')->distinct()->pluck('category')
        ]);
    }

    public function create()
    {
        $this->reset('description', 'amount', 'category', 'expenditure_date', 'supplier_id', 'notes');
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
        $this->reset('description', 'amount', 'category', 'expenditure_date', 'supplier_id', 'notes');
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
        $record = Expenditure::find($id, ['id', 'description', 'amount', 'category', 'expenditure_date', 'supplier_id', 'notes']);
        $this->selected_id = $record->id;
        $this->description = $record->description;
        $this->amount = $record->amount;
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
            'category' => $this->category,
            'expenditure_date' => $this->expenditure_date,
            'user_id' => Auth::id(),
            'supplier_id' => $this->supplier_id,
            'notes' => $this->notes,
        ]);

        // Log the notification
        $this->logNotification('Expenditure Added', 'A new expenditure record was created.', 'success');

        // Reset form fields after saving
        $this->reset(['description', 'amount', 'category', 'expenditure_date', 'supplier_id', 'notes']);

        $this->dispatch('expenditure-added', 'Expenditure Added!');
        $this->alert('success', 'Expenditure Added Successfully!');
    }

    public function Update()
    {
        $this->validate($this->rules());

        if ($this->selected_id) {
            $expenditure = Expenditure::find($this->selected_id);
            $expenditure->update([
                'description' => $this->description,
                'amount' => $this->amount,
                'category' => $this->category,
                'expenditure_date' => $this->expenditure_date,
                'supplier_id' => $this->supplier_id,
                'notes' => $this->notes,
            ]);

            // Log the notification
            $this->logNotification('Expenditure Updated', 'An expenditure record was updated.', 'info');

            $this->resetUI();
            $this->dispatch('expenditure-updated', 'Expenditure Record Updated!');
            $this->alert('success', 'Expenditure Record Updated!');
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
        $expense = Expenditure::find($this->selected_id);
        if ($expense) {
            $expense->delete();

            // Log the notification
            $this->logNotification('Expenditure Deleted', 'An expenditure record was deleted', 'warning');

            $this->alert('success', 'Successfully Deleted Expenditure record');
        } else {
            $this->alert('error', 'Unable to delete Expenditure record.');
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
