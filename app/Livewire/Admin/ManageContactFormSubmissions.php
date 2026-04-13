<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\WebsiteContactFormSubmission;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class ManageContactFormSubmissions extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = 'all';
    public $selectedSubmission = null;
    public $responseMessage = '';

    protected $rules = [
        'responseMessage' => 'required|min:10|max:2000',
    ];

    public function mount()
    {
        \App\Models\ActivityLog::log(
            'viewed_contact_submissions',
            'Admin viewed contact form submissions'
        );
    }

    public function getSubmissionsProperty()
    {
        $query = WebsiteContactFormSubmission::query();

        if ($this->search) {
            $query->where('name', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%")
                ->orWhere('subject', 'like', "%{$this->search}%");
        }

        if ($this->filterStatus !== 'all') {
            $query->where('status', $this->filterStatus);
        }

        return $query->orderBy('created_at', 'desc')->paginate(15);
    }

    public function viewSubmission($id)
    {
        $this->selectedSubmission = WebsiteContactFormSubmission::findOrFail($id);
        $this->selectedSubmission->markAsRead();

        \App\Models\ActivityLog::log(
            'viewed_contact_submission',
            "Viewed contact form submission from {$this->selectedSubmission->name}",
            'WebsiteContactFormSubmission',
            $id
        );
    }

    public function closeSubmission()
    {
        $this->selectedSubmission = null;
        $this->responseMessage = '';
    }

    public function respondToSubmission()
    {
        $this->validate();

        try {
            $this->selectedSubmission->respond($this->responseMessage, Auth::id());

            \App\Models\ActivityLog::log(
                'responded_to_contact',
                "Responded to contact form from {$this->selectedSubmission->name}",
                'WebsiteContactFormSubmission',
                $this->selectedSubmission->id
            );

            $this->dispatch('notify', [
                'title' => 'Success',
                'message' => 'Response recorded successfully',
                'type' => 'success'
            ]);

            $this->closeSubmission();
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'title' => 'Error',
                'message' => 'Failed to save response',
                'type' => 'error'
            ]);
        }
    }

    public function deleteSubmission($id)
    {
        $submission = WebsiteContactFormSubmission::findOrFail($id);

        \App\Models\ActivityLog::log(
            'deleted_contact_submission',
            "Deleted contact form from {$submission->name}",
            'WebsiteContactFormSubmission',
            $id
        );

        $submission->delete();

        $this->dispatch('notify', [
            'title' => 'Deleted',
            'message' => 'Submission deleted',
            'type' => 'success'
        ]);
    }

    public function render()
    {
        return view('livewire.admin.manage-contact-form-submissions', [
            'submissions' => $this->submissions,
        ]);
    }
}
