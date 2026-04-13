<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\NewsletterSubscriber;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class ManageNewsletterSubscribers extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = 'all';

    public function mount()
    {
        \App\Models\ActivityLog::log(
            'viewed_newsletter_subscribers',
            'Admin viewed newsletter subscribers'
        );
    }

    public function unsubscribe($id)
    {
        $subscriber = NewsletterSubscriber::findOrFail($id);
        $subscriber->unsubscribe();

        \App\Models\ActivityLog::log(
            'newsletter_unsubscribed',
            "Unsubscribed: {$subscriber->email}",
            'NewsletterSubscriber',
            $id
        );

        session()->flash('message', 'Subscriber has been unsubscribed.');
    }

    public function resubscribe($id)
    {
        $subscriber = NewsletterSubscriber::findOrFail($id);
        $subscriber->resubscribe();

        \App\Models\ActivityLog::log(
            'newsletter_resubscribed',
            "Resubscribed: {$subscriber->email}",
            'NewsletterSubscriber',
            $id
        );

        session()->flash('message', 'Subscriber has been resubscribed.');
    }

    public function delete($id)
    {
        $subscriber = NewsletterSubscriber::findOrFail($id);

        \App\Models\ActivityLog::log(
            'newsletter_subscriber_deleted',
            "Deleted subscriber: {$subscriber->email}",
            'NewsletterSubscriber',
            $id
        );

        $subscriber->delete();

        session()->flash('message', 'Subscriber deleted successfully.');
    }

    public function exportCsv()
    {
        $subscribers = NewsletterSubscriber::active()->get();
        $csv = "Email,Name,Subscribed Date\n";

        foreach ($subscribers as $sub) {
            $csv .= "\"{$sub->email}\",\"{$sub->name}\",\"{$sub->subscribed_at->format('Y-m-d')}\"\n";
        }

        return response()->streamDownload(function () use ($csv) {
            echo $csv;
        }, 'newsletter-subscribers-' . date('Y-m-d') . '.csv');
    }

    public function render()
    {
        $query = NewsletterSubscriber::query();

        if ($this->search) {
            $query->where('email', 'like', "%{$this->search}%")
                ->orWhere('name', 'like', "%{$this->search}%");
        }

        if ($this->filterStatus === 'active') {
            $query->where('is_active', true);
        } elseif ($this->filterStatus === 'inactive') {
            $query->where('is_active', false);
        }

        $subscribers = $query->orderBy('subscribed_at', 'desc')->paginate(20);

        return view('livewire.admin.manage-newsletter-subscribers', [
            'subscribers' => $subscribers,
            'totalSubscribers' => NewsletterSubscriber::count(),
            'activeSubscribers' => NewsletterSubscriber::active()->count(),
        ]);
    }
}
