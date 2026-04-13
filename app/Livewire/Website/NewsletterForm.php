<?php

namespace App\Livewire\Website;

use Livewire\Component;
use App\Models\NewsletterSubscriber;

class NewsletterForm extends Component
{
    public $email = '';
    public $name = '';
    public $successMessage = '';

    protected $rules = [
        'email' => 'required|email|unique:newsletter_subscribers',
        'name' => 'nullable|string|max:255',
    ];

    protected $messages = [
        'email.unique' => 'This email is already subscribed.',
    ];

    public function subscribe()
    {
        $this->successMessage = '';
        $this->validate();

        try {
            NewsletterSubscriber::create([
                'email' => $this->email,
                'name' => $this->name,
                'ip_address' => request()->ip(),
                'is_active' => true,
            ]);

            \App\Models\ActivityLog::log(
                'newsletter_subscribed',
                "New newsletter subscriber: {$this->email}",
                'NewsletterSubscriber'
            );

            $this->successMessage = 'Thank you for subscribing! Check your inbox for confirmation.';
            $this->reset(['email', 'name']);
        } catch (\Exception $e) {
            if (str_contains($e->getMessage(), 'unique')) {
                $this->addError('email', 'This email is already subscribed.');
            } else {
                $this->addError('email', 'Failed to subscribe. Please try again.');
            }
        }
    }

    public function render()
    {
        return view('livewire.website.newsletter-form');
    }
}
