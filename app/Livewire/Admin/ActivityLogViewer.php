<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\ActivityLog;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class ActivityLogViewer extends Component
{
    use WithPagination;

    public $search = '';
    public $filterUser = '';
    public $filterAction = '';
    public $filterDays = 7;

    public function mount()
    {
        ActivityLog::log(
            'viewed_activity_logs',
            'Admin viewed activity logs'
        );
    }

    public function getActionsProperty()
    {
        return ActivityLog::distinct()->pluck('action')->sort();
    }

    public function getUsersProperty()
    {
        return \App\Models\User::whereHas('activityLogs')
            ->pluck('name', 'id')
            ->sort();
    }

    public function render()
    {
        $query = ActivityLog::with('user')
            ->where('created_at', '>=', now()->subDays($this->filterDays));

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('description', 'like', "%{$this->search}%")
                    ->orWhere('action', 'like', "%{$this->search}%")
                    ->orWhereHas('user', function ($q) {
                        $q->where('name', 'like', "%{$this->search}%");
                    });
            });
        }

        if ($this->filterUser) {
            $query->where('user_id', $this->filterUser);
        }

        if ($this->filterAction) {
            $query->where('action', $this->filterAction);
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(25);

        return view('livewire.admin.activity-log-viewer', [
            'logs' => $logs,
            'actions' => $this->actions,
            'users' => $this->users,
        ]);
    }
}
