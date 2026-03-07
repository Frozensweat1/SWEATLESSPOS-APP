<?php

namespace App\Livewire;

use App\Models\Notification;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf;

#[Layout('layouts.app')]
class Notifications extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $filterType = '';
    public $filterReadStatus = '';
    public $selectedNotifications = [];
    public $selectAll = false;
    public $perPage = 10;
    public $selected_id;
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedSelectAll($value)
    {
        $this->selectedNotifications = $value ? $this->getNotificationsQuery()->pluck('id')->toArray() : [];
    }

    public function updatedSelectedNotifications()
    {
        $this->selectAll = count($this->selectedNotifications) === $this->getNotificationsQuery()->count();
    }

    public function getNotificationsQuery()
    {
        return Notification::query()
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('message', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterType, function ($query) {
                $query->where('type', $this->filterType);
            })
            ->when($this->filterReadStatus, function ($query) {
                $query->where('is_read', $this->filterReadStatus === 'read');
            });
    }
    public function confirmBulkDelete()
    {
        if (count($this->selectedNotifications) > 0) {
            LivewireAlert::title('Are you sure?')
                ->html('You are about to delete selected notifications.')
                ->warning()
                ->onConfirm('bulkDeleteNotifications')
                ->withConfirmButton('Yes, Delete')
                ->withCancelButton('Cancel')
                ->show();
        } else {
            LivewireAlert::warning('No notifications selected.')
                ->toast()
                ->position('top-end')
                ->show();
        }
    }
    public function bulkDeleteNotifications()
    {
        Notification::whereIn('id', $this->selectedNotifications)->delete();
        LivewireAlert::title('Success')
            ->text('Selected notifications deleted successfully')
            ->success()
            ->toast()
            ->position('top-end')
            ->show();
        $this->selectedNotifications = [];
    }

    public function delete($id)
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
        $notification = Notification::find($this->selected_id);
        if ($notification) {
            $notification->delete();
            LivewireAlert::title('Deleted')
                ->text('Successfully Deleted ' . $notification->title)
                ->success()
                ->toast()
                ->position('top-end')
                ->show();
        } else {
            LivewireAlert::title('Error')
                ->text('Unable to delete notification record.')
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
        }
    }
    protected $listeners = [
        'confirmedDeletion',
        'bulkDeleteNotifications'
    ];
    public function export($format)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'User ID');
        $sheet->setCellValue('B1', 'Title');
        $sheet->setCellValue('C1', 'Message');
        $sheet->setCellValue('D1', 'Type');
        $sheet->setCellValue('E1', 'Read Status');
        $sheet->setCellValue('F1', 'Read At');
        $sheet->setCellValue('G1', 'Created At');

        $row = 2;

        foreach ($this->getNotificationsQuery()->get() as $notification) {
            $sheet->setCellValue("A{$row}", $notification->user_id);
            $sheet->setCellValue("B{$row}", $notification->title);
            $sheet->setCellValue("C{$row}", $notification->message);
            $sheet->setCellValue("D{$row}", $notification->type);
            $sheet->setCellValue("E{$row}", $notification->is_read ? 'Read' : 'Unread');
            $sheet->setCellValue("F{$row}", $notification->read_at);
            $sheet->setCellValue("G{$row}", $notification->created_at->format('Y-m-d H:i:s'));
            $row++;
        }

        $fileName = 'notifications.' . $format;
        $writer = match ($format) {
            'xlsx' => new Xlsx($spreadsheet),
            'csv' => new Csv($spreadsheet),
            'pdf' => new Mpdf($spreadsheet),
            default => new Xlsx($spreadsheet)
        };

        $filePath = storage_path("app/public/{$fileName}");
        $writer->save($filePath);

        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    public function render()
    {
        return view('livewire.notifications', [
            'notifications' => $this->getNotificationsQuery()->paginate($this->perPage),
        ]);
    }
}
