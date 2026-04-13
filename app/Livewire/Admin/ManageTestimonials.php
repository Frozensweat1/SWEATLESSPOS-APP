<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class ManageTestimonials extends Component
{
    use WithPagination, WithFileUploads;

    public $showForm = false;
    public $editingId = null;
    public $authorName = '';
    public $authorTitle = '';
    public $authorImage = null;
    public $testimonialText = '';
    public $rating = 5;
    public $isApproved = false;
    public $isFeatured = false;
    public $publishedDate = '';
    public $search = '';

    protected $rules = [
        'authorName' => 'required|string|max:255',
        'authorTitle' => 'nullable|string|max:255',
        'testimonialText' => 'required|string|min:10',
        'rating' => 'required|integer|min:1|max:5',
        'publishedDate' => 'nullable|date',
    ];

    public function create()
    {
        $this->reset(['editingId', 'authorName', 'authorTitle', 'testimonialText', 'rating', 'authorImage']);
        $this->publishedDate = today()->toDateString();
        $this->showForm = true;
    }

    public function edit($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $this->editingId = $id;
        $this->authorName = $testimonial->author_name;
        $this->authorTitle = $testimonial->author_title;
        $this->testimonialText = $testimonial->testimonial_text;
        $this->rating = $testimonial->rating;
        $this->isApproved = $testimonial->is_approved;
        $this->isFeatured = $testimonial->is_featured;
        $this->publishedDate = $testimonial->published_date?->toDateString();
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate();

        try {
            $data = [
                'author_name' => $this->authorName,
                'author_title' => $this->authorTitle,
                'testimonial_text' => $this->testimonialText,
                'rating' => $this->rating,
                'is_approved' => $this->isApproved,
                'is_featured' => $this->isFeatured,
                'published_date' => $this->publishedDate,
                'created_by' => Auth::id(),
            ];

            if ($this->authorImage) {
                $data['author_image'] = $this->authorImage->store('testimonials', 'public');
            }

            if ($this->editingId) {
                $testimonial = Testimonial::findOrFail($this->editingId);
                $testimonial->update($data);
                $action = 'updated';
            } else {
                Testimonial::create($data);
                $action = 'created';
            }

            \App\Models\ActivityLog::log(
                'testimonial_' . $action,
                "Testimonial $action by " . $this->authorName,
                'Testimonial',
                $this->editingId
            );

            session()->flash('message', "Testimonial $action successfully.");

            $this->reset();
            $this->showForm = false;
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function delete($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        \App\Models\ActivityLog::log(
            'testimonial_deleted',
            "Testimonial deleted: {$testimonial->author_name}",
            'Testimonial',
            $id
        );
        $testimonial->delete();

        session()->flash('message', 'Testimonial deleted successfully.');
    }

    public function render()
    {
        $query = Testimonial::query();

        if ($this->search) {
            $query->where('author_name', 'like', "%{$this->search}%")
                ->orWhere('testimonial_text', 'like', "%{$this->search}%");
        }

        $testimonials = $query->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.manage-testimonials', [
            'testimonials' => $testimonials,
        ]);
    }
}
