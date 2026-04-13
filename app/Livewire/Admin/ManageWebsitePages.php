<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\WebsitePage;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

#[Layout('layouts.app')]
class ManageWebsitePages extends Component
{
    use WithPagination;

    public $pageId = null;
    public $title = '';
    public $slug = '';
    public $content = '';
    public $meta_title = '';
    public $meta_description = '';
    public $is_active = true;
    public $search = '';
    public $editingMode = false;

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'title' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:website_pages,slug',
        'content' => 'nullable|string',
        'meta_title' => 'nullable|string|max:255',
        'meta_description' => 'nullable|string|max:255',
        'is_active' => 'boolean',
    ];

    public function render()
    {
        $pages = WebsitePage::query()
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('slug', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);

        return view('livewire.admin.manage-website-pages', [
            'pages' => $pages,
        ]);
    }

    public function edit($id)
    {
        $page = WebsitePage::findOrFail($id);
        $this->pageId = $page->id;
        $this->title = $page->title;
        $this->slug = $page->slug;
        $this->content = $page->content;
        $this->meta_title = $page->meta_title;
        $this->meta_description = $page->meta_description;
        $this->is_active = $page->is_active;
        $this->editingMode = true;
    }

    public function save()
    {
        $rules = $this->rules;
        if ($this->editingMode) {
            $rules['slug'] = 'required|string|max:255|unique:website_pages,slug,' . $this->pageId;
        }

        $this->validate($rules);

        if ($this->editingMode) {
            $page = WebsitePage::findOrFail($this->pageId);
            $page->update([
                'title' => $this->title,
                'slug' => $this->slug,
                'content' => $this->content,
                'meta_title' => $this->meta_title,
                'meta_description' => $this->meta_description,
                'is_active' => $this->is_active,
                'updated_by' => Auth::id(),
            ]);
        } else {
            WebsitePage::create([
                'title' => $this->title,
                'slug' => $this->slug,
                'content' => $this->content,
                'meta_title' => $this->meta_title,
                'meta_description' => $this->meta_description,
                'is_active' => $this->is_active,
                'created_by' => Auth::id(),
            ]);
        }

        LivewireAlert::title('Success')
            ->text('Page ' . ($this->editingMode ? 'updated' : 'created') . ' successfully.')
            ->success()
            ->toast()
            ->show();

        $this->resetForm();
    }

    public function delete($id)
    {
        WebsitePage::findOrFail($id)->delete();
        LivewireAlert::title('Deleted')
            ->text('Page deleted successfully.')
            ->success()
            ->toast()
            ->show();
    }

    public function resetForm()
    {
        $this->pageId = null;
        $this->title = '';
        $this->slug = '';
        $this->content = '';
        $this->meta_title = '';
        $this->meta_description = '';
        $this->is_active = true;
        $this->editingMode = false;
        $this->resetPage();
    }
}
