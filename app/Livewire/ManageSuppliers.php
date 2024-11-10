<?php

namespace App\Livewire;

use App\Models\Supplier;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Response;

#[Layout('layouts.app')]
class ManageSuppliers extends Component
{
    use WithFileUploads, WithPagination, LivewireAlert;

    protected $paginationTheme = 'bootstrap';

    public $name, $contact_person, $address, $email, $phone, $notes, $supplierId;
    public $componentName, $currentImage, $searchTerm, $pageTitle, $isEditMode = false;
    public $selected_id, $selectedSuppliers = [];
    public $startDate, $endDate;
    public $selectAll = false;
    public $perPage = 10;
    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|unique:suppliers,email,' . $this->selected_id,
            'phone' => 'required|numeric|unique:suppliers,phone,' . $this->selected_id,
            'address' => 'required|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function mount()
    {
        $this->pageTitle = 'Manage Suppliers';
        $this->componentName = 'Suppliers';
    }
    public function confirmBulkDelete()
    {
        if (count($this->selectedSuppliers) > 0) {
            $this->alert('warning', 'Are you sure?', [
                'text' => 'You are about to delete selected suppliers.',
                'showConfirmButton' => true,
                'confirmButtonText' => 'Yes, Delete',
                'onConfirmed' => 'bulkDeleteSuppliers',
                'showCancelButton' => true,
                'cancelButtonText' => 'Cancel',
            ]);
        } else {
            $this->alert('warning', 'No suppliers selected.');
        }
    }
    public function bulkDeleteSuppliers()
    {
        $suppliers = Supplier::whereIn('id', $this->selectedSuppliers)->get();
        $deletedNames = $suppliers->pluck('name')->toArray();
        Supplier::whereIn('id', $this->selectedSuppliers)->delete();
        $this->logNotification('Suppliers Bulk Deleted', 'Suppliers: ' . implode(', ', $deletedNames) . ' were deleted.');
        $this->alert('success', 'Selected suppliers deleted successfully.');
        $this->reset('selectedSuppliers');
    }

    public function updatedSelectAll($value)
    {
        $this->selectedSuppliers = $value ? $this->getSuppliersProperty()->pluck('id')->toArray() : [];
    }

    public function updatedSelectedSuppliers()
    {
        $this->selectAll = count($this->selectedSuppliers) === $this->getSuppliersProperty()->count();
    }
    public function getSuppliersProperty()
    {
        $query = Supplier::query()
            ->when($this->searchTerm, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('name', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('email', 'like', '%' . $this->searchTerm . '%');
                });
            })
            ->when($this->startDate && $this->endDate, function ($query) {
                $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
            })->orderBy('created_at', 'desc');
        return $query->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.manage-suppliers', [
            'suppliers' => $this->suppliers
        ]);
    }
    public function toggleSelectAll()
    {
        if ($this->selectAll) {
            $this->selectedSuppliers = $this->suppliers->pluck('id')->toArray();
        } else {
            $this->selectedSuppliers = [];
        }
    }
    public function export()
    {
        $query = Supplier::query();

        if ($this->searchTerm) {
            $query->where(function ($subQuery) {
                $subQuery->where('name', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('email', 'like', '%' . $this->searchTerm . '%');
            });
        }

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
        }

        $suppliers = $query->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set header row
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Name');
        $sheet->setCellValue('C1', 'Contact Person');
        $sheet->setCellValue('D1', 'Email');
        $sheet->setCellValue('E1', 'Phone');
        $sheet->setCellValue('F1', 'Address');
        $sheet->setCellValue('G1', 'Notes');
        $sheet->setCellValue('H1', 'Created At');

        // Populate data rows
        $row = 2;
        foreach ($suppliers as $supplier) {
            $sheet->setCellValue('A' . $row, $supplier->id);
            $sheet->setCellValue('B' . $row, $supplier->name);
            $sheet->setCellValue('C' . $row, $supplier->contact_person);
            $sheet->setCellValue('D' . $row, $supplier->email);
            $sheet->setCellValue('E' . $row, $supplier->phone);
            $sheet->setCellValue('F' . $row, $supplier->address);
            $sheet->setCellValue('G' . $row, $supplier->notes);
            $sheet->setCellValue('H' . $row, $supplier->created_at->format('Y-m-d H:i:s'));
            $row++;
        }

        // Generate the file
        $writer = new Xlsx($spreadsheet);
        $fileName = 'suppliers_export_' . now()->format('Y_m_d_H_i_s') . '.xlsx';
        $filePath = storage_path('app/public/' . $fileName);

        $writer->save($filePath);

        return Response::download($filePath)->deleteFileAfterSend(true);
    }


    public function create()
    {
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
        $this->resetUI();
    }

    public function Edit($id)
    {
        $this->resetUI();
        $this->isEditMode = true;
        $supplier = Supplier::find($id);
        $this->selected_id = $supplier->id;
        $this->name = $supplier->name;
        $this->contact_person = $supplier->contact_person;
        $this->email = $supplier->email;
        $this->phone = $supplier->phone;
        $this->address = $supplier->address;
        $this->notes = $supplier->notes;
        $this->openModal();
    }

    public function Store()
    {
        $this->validate();

        $supplier = Supplier::create([
            'name' => $this->name,
            'contact_person' => $this->contact_person,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'notes' => $this->notes
        ]);

        $this->logNotification('Supplier Created', "Supplier '{$supplier->name}' created successfully.");
        $this->resetUI();
        $this->alert('success', 'Supplier Registered Successfully!');
    }

    public function Update()
    {
        $this->validate();

        if ($this->selected_id) {
            $supplier = Supplier::find($this->selected_id);

            $supplier->update([
                'name' => $this->name,
                'contact_person' => $this->contact_person,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
                'notes' => $this->notes
            ]);

            $this->logNotification('Supplier Updated', "Supplier '{$supplier->name}' updated successfully.");
            $this->resetUI();
            $this->alert('success', 'Supplier Record Updated!');
        }
    }

    public function delete($id)
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
        $supplier = Supplier::find($this->selected_id);
        if ($supplier) {
            $supplier->delete();
            $this->logNotification('Supplier Deleted', "Supplier '{$supplier->name}' deleted successfully.");
            $this->alert('success', 'Successfully Deleted ' . $supplier->name);
        } else {
            $this->alert('error', 'Unable to delete supplier record.');
        }
    }
    protected $listeners = [
        'confirmedDeletion',
        'bulkDeleteSuppliers'
    ];
    protected function logNotification($title, $message)
    {
        Notification::create([
            'user_id' => Auth::id(),
            'title' => $title,
            'message' => $message . " Action by " . Auth::user()->name . ".",
            'type' => 'info',
            'is_read' => false,
        ]);
    }

    public function resetUI()
    {
        $this->name = '';
        $this->contact_person = '';
        $this->email = '';
        $this->phone = '';
        $this->address = '';
        $this->notes = '';
        $this->supplierId = null;
        $this->currentImage = null;
        $this->selected_id = 0;
        $this->selectedSuppliers = [];
    }
}
