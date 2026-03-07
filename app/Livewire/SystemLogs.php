<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use App\Models\Log; // Assuming the model is named SystemLog
use Livewire\WithFileUploads;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class SystemLogs extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $dateFrom;
    public $dateTo;
    public $selectedUser;
    public $selectedLogs = [];
    public $selectAll = false;

    protected $queryString = ['search', 'dateFrom', 'dateTo', 'selectedUser'];

    public function updatedSelectAll($value)
    {
        $this->selectedLogs = $value ? $this->logs->pluck('id')->toArray() : [];
    }

    public function deleteSelected()
    {
        LivewireAlert::title('Are you sure you want to delete the selected logs?')
            ->warning()
            ->onConfirm('bulkDelete')
            ->withConfirmButton('Yes, Delete')
            ->withCancelButton('Cancel')
            ->show();
    }

    public function bulkDelete()
    {
        Log::whereIn('id', $this->selectedLogs)->delete();
        $this->selectedLogs = [];
        $this->selectAll = false;
        LivewireAlert::title('Success')
            ->text('Selected logs deleted successfully.')
            ->success()
            ->toast()
            ->position('top-end')
            ->show();
    }

    public function export()
    {
        // Logic for export
    }

    public function getLogsProperty()
    {
        $query = Log::query();

        if ($this->search) {
            $query->where('description', 'like', "%{$this->search}%");
        }

        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', Carbon::parse($this->dateFrom));
        }

        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', Carbon::parse($this->dateTo));
        }

        if ($this->selectedUser) {
            $query->where('user_id', $this->selectedUser);
        }

        return $query->paginate(10);
    }

    public function render()
    {
        $users = DB::table('users')->select('id', 'name')->get();

        return view('livewire.system-logs', [
            'logs' => $this->logs,
            'users' => $users,
        ]);
    }
}
