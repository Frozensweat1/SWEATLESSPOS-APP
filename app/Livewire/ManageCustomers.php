<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\WithFileUploads;
use App\Models\Customer;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Response;

#[Layout('layouts.app')]
class ManageCustomers extends Component
{
    use WithFileUploads, WithPagination, LivewireAlert;

    protected $paginationTheme = 'bootstrap'; // Using Bootstrap for pagination UI
    public $name, $address, $image, $email, $phone, $loyalty_points = 0, $customerId;
    public $componentName;
    public $currentImage;
    public $searchengine;
    public $pageTitle;
    public $isEditMode = false;
    public $selected_id;
    public $selectedCustomers = [];
    public $selectAll = false;
    public $startDate, $endDate;
    public $perPage = 10;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|max:255|unique:customers,email,' . $this->selected_id,
            'phone' => 'required|string|max:15|unique:customers,phone,' . $this->selected_id,
            'address' => 'required|string|max:255',
            'image' => 'nullable|image|max:5024',
        ];
    }

    public function mount()
    {
        $this->pageTitle = 'Data';
        $this->componentName = 'Customers';
    }
    public function confirmBulkDelete()
    {
        if (count($this->selectedCustomers) > 0) {
            $this->alert('warning', 'Are you sure?', [
                'text' => 'You are about to delete selected customers.',
                'showConfirmButton' => true,
                'confirmButtonText' => 'Yes, Delete',
                'onConfirmed' => 'bulkDeleteCustomers',
                'showCancelButton' => true,
                'cancelButtonText' => 'Cancel',
            ]);
        } else {
            $this->alert('warning', 'No customers selected.');
        }
    }
    public function bulkDeleteCustomers()
    {
        foreach ($this->selectedCustomers as $customerId) {
            $customer = Customer::find($customerId);
            if ($customer) {
                $customer->delete();
                $this->logNotification('Customer Deleted', 'Deleted customer: ' . $customer->name);
            }
        }
        $this->selectedCustomers = [];
        $this->alert('success', 'Selected customers deleted successfully.');
    }

    public function updatedSelectAll($value)
    {
        $this->selectedCustomers = $value ? $this->getCustomersProperty()->pluck('id')->toArray() : [];
    }

    public function updatedSelectedProducts()
    {
        $this->selectAll = count($this->selectedProducts) === $this->getCustomersProperty()->count();
    }
    public function getCustomersProperty()
    {
        $query = Customer::query()
            ->when($this->searchengine, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->searchengine . '%')
                        ->orWhere('email', 'like', '%' . $this->searchengine . '%');
                });
            })
            ->when($this->startDate, function ($query) {
                $query->whereDate('created_at', '>=', $this->startDate);
            })
            ->when($this->endDate, function ($query) {
                $query->whereDate('created_at', '<=', $this->endDate);
            })
            ->orderBy('created_at', 'desc');
        return $query->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.manage-customers', [
            'customers' => $this->customers
        ]);
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
        $record = Customer::find($id, ['id', 'name', 'email', 'phone', 'address', 'image']);
        $this->selected_id = $record->id;
        $this->name = $record->name;
        $this->email = $record->email;
        $this->phone = $record->phone;
        $this->address = $record->address;
        $this->currentImage = $record->image;
        $this->openModal();
    }

    public function Store()
    {
        $this->validate($this->rules());
        $customer = Customer::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
        ]);
        // Handle image upload, if provided
        if ($this->image) {
            // Store the image in the 'customers' directory within the 'public' disk
            $imagePath = $this->image->store('customers', 'public');
            // Update the customer with the image path
            $customer->update(['image' => $imagePath]);
        }
        $this->resetUI();
        $this->logNotification('Customer Created', 'Added customer: ' . $customer->name);
        $this->alert('success', 'Customer Registered Successfully!');
    }

    public function Update()
    {
        $this->validate($this->rules());
        if ($this->selected_id) {
            $customer = Customer::find($this->selected_id);
            $data = [
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
            ];

            if ($this->image) {
                $data['image'] = $this->image->store('customers', 'public');
            }

            $customer->update($data);
            $this->resetUI();
            $this->logNotification('Customer Updated', 'Updated customer: ' . $customer->name);
            $this->alert('success', 'Customer Record Updated!');
        }
    }
    public function export()
    {
        // Build the query for customers
        $query = Customer::query();

        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }

        if ($this->searchengine) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->searchengine . '%')
                    ->orWhere('email', 'like', '%' . $this->searchengine . '%');
            });
        }

        // Retrieve the filtered customer data
        $customers = $query->get(['name', 'email', 'phone', 'address', 'loyalty_points', 'image', 'status']);

        // Create a new spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set the header
        $sheet->setCellValue('A1', 'Name');
        $sheet->setCellValue('B1', 'Email');
        $sheet->setCellValue('C1', 'Phone');
        $sheet->setCellValue('D1', 'Address');
        $sheet->setCellValue('E1', 'Loyalty Points');
        $sheet->setCellValue('F1', 'Image');
        $sheet->setCellValue('G1', 'Status');

        // Fill the spreadsheet with customer data
        $row = 2;
        foreach ($customers as $customer) {
            $sheet->setCellValue('A' . $row, $customer->name);
            $sheet->setCellValue('B' . $row, $customer->email);
            $sheet->setCellValue('C' . $row, $customer->phone);
            $sheet->setCellValue('D' . $row, $customer->address);
            $sheet->setCellValue('E' . $row, $customer->loyalty_points);
            $sheet->setCellValue('F' . $row, $customer->image ? asset('storage/' . $customer->image) : 'No Image');
            $sheet->setCellValue('G' . $row, $customer->status);
            $row++;
        }

        // Set the filename and download the file
        $fileName = 'customers_' . date('Y-m-d') . '.xlsx';

        // Create a writer and save the file to output
        $writer = new Xlsx($spreadsheet);

        // Optional: for CSV export, uncomment below and change the writer type
        // $writer = new Csv($spreadsheet);

        $filePath = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($filePath);

        return Response::download($filePath, $fileName)->deleteFileAfterSend(true);
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
            'width' => '400',
        ]);
    }

    public function confirmedDeletion()
    {
        $customer = Customer::find($this->selected_id);
        if ($customer) {
            $customer->delete();
            $this->logNotification('Customer Deleted', 'Deleted customer: ' . $customer->name);
            $this->alert('success', 'Successfully Deleted ' . $customer->name);
        } else {
            $this->alert('error', 'Unable to delete customer record.');
        }
    }

    protected $listeners = [
        'confirmedDeletion',
        'bulkDeleteCustomers'
    ];

    private function logNotification($title, $message)
    {
        Notification::create([
            'user_id' => Auth::id(),
            'title' => $title,
            'message' => $message,
            'type' => 'customer',
            'is_read' => false,
        ]);
    }

    public function resetUI()
    {
        $this->reset(['name', 'email', 'phone', 'address', 'loyalty_points', 'image', 'customerId', 'currentImage', 'selected_id', 'selectedCustomers']);
    }
}
