<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\GalleryAlbum;
use App\Models\GalleryImage;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

#[Layout('layouts.app')]
class ManageGallery extends Component
{
    use WithPagination, WithFileUploads;

    public $tab = 'albums'; // albums or images
    public $albumId = null;
    public $albumName = '';
    public $albumSlug = '';
    public $albumDescription = '';
    public $albumDisplayOrder = 0;
    public $albumIsActive = true;
    public $editingAlbumId = null;

    public $imageTitle = '';
    public $imageDescription = '';
    public $imageDisplayOrder = 0;
    public $imageFeatured = false;
    public $imageFile;
    public $selectedAlbumId = null;
    public $editingImageId = null;

    public $search = '';

    protected $paginationTheme = 'bootstrap';

    protected $albumRules = [
        'albumName' => 'required|string|max:255',
        'albumSlug' => 'required|string|max:255|unique:gallery_albums,slug',
        'albumDescription' => 'nullable|string',
        'albumDisplayOrder' => 'integer|min:0',
        'albumIsActive' => 'boolean',
    ];

    protected $imageRules = [
        'imageTitle' => 'nullable|string|max:255',
        'imageDescription' => 'nullable|string',
        'imageDisplayOrder' => 'integer|min:0',
        'imageFeatured' => 'boolean',
        'imageFile' => 'required|image|max:5120', // 5MB
        'selectedAlbumId' => 'required|exists:gallery_albums,id',
    ];

    public function render()
    {
        if ($this->tab === 'albums') {
            $albums = GalleryAlbum::query()
                ->when($this->search, function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%');
                })
                ->paginate(10);

            return view('livewire.admin.manage-gallery', [
                'albums' => $albums,
                'categories' => null,
            ]);
        } else {
            $album = GalleryAlbum::with('images')->find($this->selectedAlbumId);
            $images = $album ? $album->images()->paginate(12) : collect();

            return view('livewire.admin.manage-gallery', [
                'albums' => collect(),
                'categories' => $images,
                'currentAlbum' => $album,
            ]);
        }
    }

    public function editAlbum($id)
    {
        $album = GalleryAlbum::findOrFail($id);
        $this->editingAlbumId = $album->id;
        $this->albumName = $album->name;
        $this->albumSlug = $album->slug;
        $this->albumDescription = $album->description;
        $this->albumDisplayOrder = $album->display_order;
        $this->albumIsActive = $album->is_active;
    }

    public function saveAlbum()
    {
        $rules = $this->albumRules;
        if ($this->editingAlbumId) {
            $rules['albumSlug'] = 'required|string|max:255|unique:gallery_albums,slug,' . $this->editingAlbumId;
        }

        $this->validate($rules);

        if ($this->editingAlbumId) {
            $album = GalleryAlbum::findOrFail($this->editingAlbumId);
            $album->update([
                'name' => $this->albumName,
                'slug' => $this->albumSlug,
                'description' => $this->albumDescription,
                'display_order' => $this->albumDisplayOrder,
                'is_active' => $this->albumIsActive,
            ]);
        } else {
            GalleryAlbum::create([
                'name' => $this->albumName,
                'slug' => $this->albumSlug,
                'description' => $this->albumDescription,
                'display_order' => $this->albumDisplayOrder,
                'is_active' => $this->albumIsActive,
                'created_by' => Auth::id(),
            ]);
        }

        LivewireAlert::title('Success')
            ->text('Album ' . ($this->editingAlbumId ? 'updated' : 'created') . ' successfully.')
            ->success()
            ->toast()
            ->show();

        $this->resetAlbumForm();
    }

    public function deleteAlbum($id)
    {
        GalleryAlbum::findOrFail($id)->delete();
        LivewireAlert::title('Deleted')
            ->text('Album deleted successfully.')
            ->success()
            ->toast()
            ->show();
    }

    public function selectAlbum($id)
    {
        $this->selectedAlbumId = $id;
        $this->tab = 'images';
    }

    public function uploadImage()
    {
        $this->validate($this->imageRules);

        $path = $this->imageFile->store('gallery', 'public');

        GalleryImage::create([
            'gallery_album_id' => $this->selectedAlbumId,
            'image_path' => $path,
            'title' => $this->imageTitle,
            'description' => $this->imageDescription,
            'display_order' => $this->imageDisplayOrder,
            'is_featured' => $this->imageFeatured,
            'uploaded_by' => Auth::id(),
        ]);

        LivewireAlert::title('Success')
            ->text('Image uploaded successfully.')
            ->success()
            ->toast()
            ->show();

        $this->resetImageForm();
    }

    public function deleteImage($id)
    {
        $image = GalleryImage::findOrFail($id);
        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        LivewireAlert::title('Deleted')
            ->text('Image deleted successfully.')
            ->success()
            ->toast()
            ->show();
    }

    public function resetAlbumForm()
    {
        $this->editingAlbumId = null;
        $this->albumName = '';
        $this->albumSlug = '';
        $this->albumDescription = '';
        $this->albumDisplayOrder = 0;
        $this->albumIsActive = true;
    }

    public function resetImageForm()
    {
        $this->imageTitle = '';
        $this->imageDescription = '';
        $this->imageDisplayOrder = 0;
        $this->imageFeatured = false;
        $this->imageFile = null;
    }

    public function backToAlbums()
    {
        $this->tab = 'albums';
        $this->selectedAlbumId = null;
    }
}
