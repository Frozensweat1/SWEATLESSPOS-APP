<?php

namespace App\Livewire\Website;

use Livewire\Component;
use App\Models\WebsiteContactFormSubmission;
use Livewire\Attributes\Layout;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

#[Layout('layouts.website')]
class ContactFormSubmission extends Component
{
    public $name = '';
    public $email = '';
    public $phone = '';
    public $subject = '';
    public $message = '';
    public bool $sent = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'phone' => 'nullable|string|max:20',
        'subject' => 'required|string|max:255',
        'message' => 'required|string|min:10|max:5000',
    ];

    public function submitForm()
    {
        $this->validate();

        try {
            WebsiteContactFormSubmission::create([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'subject' => $this->subject,
                'message' => $this->message,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'status' => 'new',
            ]);

            LivewireAlert::title('Thank You!')
                ->text('Your message has been sent successfully. We will get back to you soon.')
                ->success()
                ->show();

            // Reset form
            $name = $this->name;
            $this->reset(['name', 'email', 'phone', 'subject', 'message']);
            $this->sent = true;

            // Log activity
            \App\Models\ActivityLog::log(
                'contact_form_submitted',
                "New contact form submission from {$name}",
                'WebsiteContactFormSubmission'
            );
        } catch (\Exception $e) {
            LivewireAlert::title('Error')
                ->text('Failed to send your message. Please try again.')
                ->error()
                ->show();
        }
    }

    public function render()
    {
        return view('livewire.website.contact-form-submission');
    }
}
