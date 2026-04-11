<?php

namespace App\Livewire;

use App\Models\CashDrawerSession;
use App\Services\CashDrawerService;
use App\Traits\StoreScoped;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

#[Layout('layouts.app')]
class ManageCashDrawer extends Component
{
    use WithPagination;
    use StoreScoped;

    protected $paginationTheme = 'bootstrap';

    public $componentName = 'Cash Drawer';
    public $pageTitle = 'Opening & Closing';

    public $opening_balance;
    public $closing_balance;
    public $opening_note;
    public $closing_note;

    public $search = '';
    public $statusFilter = '';
    public $startDate;
    public $endDate;
    public $perPage = 10;

    protected ?CashDrawerService $cashDrawerService = null;

    public function mount()
    {
        $this->initStoreId();
        $this->cashDrawerService = app(CashDrawerService::class);
    }

    protected function getCashDrawerService(): CashDrawerService
    {
        if ($this->cashDrawerService === null) {
            $this->cashDrawerService = app(CashDrawerService::class);
        }
        return $this->cashDrawerService;
    }

    public function updated($propertyName)
    {
        if ($propertyName !== 'perPage') {
            $this->resetPage();
        }
    }

    public function openDrawer()
    {
        $this->validate([
            'opening_balance' => 'required|numeric|min:0',
            'opening_note' => 'nullable|string',
        ]);

        try {
            $this->getCashDrawerService()->openDrawer(
                $this->getStoreId(),
                $this->opening_balance,
                $this->opening_note
            );

            $this->reset(['opening_balance', 'opening_note']);

            LivewireAlert::title('Success')
                ->text('Cash drawer opened.')
                ->success()
                ->toast()
                ->position('top-end')
                ->show();
        } catch (\Exception $e) {
            LivewireAlert::title('Warning')
                ->text($e->getMessage())
                ->warning()
                ->toast()
                ->position('top-end')
                ->show();
        }
    }

    public function closeDrawer()
    {
        $this->validate([
            'closing_balance' => 'required|numeric|min:0',
            'closing_note' => 'nullable|string',
        ]);

        try {
            $this->getCashDrawerService()->closeDrawer(
                $this->getStoreId(),
                $this->closing_balance,
                $this->closing_note
            );

            $this->reset(['closing_balance', 'closing_note']);

            LivewireAlert::title('Success')
                ->text('Cash drawer closed.')
                ->success()
                ->toast()
                ->position('top-end')
                ->show();
        } catch (\Exception $e) {
            LivewireAlert::title('Error')
                ->text($e->getMessage())
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
        }
    }

    private function getOpenSession()
    {
        return $this->getCashDrawerService()->getOpenSession($this->getStoreId());
    }

    public function render()
    {
        $openSession = $this->getOpenSession();
        $liveSummary = null;

        if ($openSession) {
            $summary = $this->getCashDrawerService()->getLiveSummary($openSession);
            $liveSummary = array_merge($summary, [
                'discrepancy' => $this->closing_balance !== null
                    ? (float) $this->closing_balance - $summary['expected_cash']
                    : null,
            ]);
        }

        $sessions = CashDrawerSession::where('store_id', $this->getStoreId())
            ->with('user')
            ->when($this->search, fn($q) => $q->where('id', $this->search))
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->when($this->startDate, fn($q) => $q->whereDate('opened_at', '>=', $this->startDate))
            ->when($this->endDate, fn($q) => $q->whereDate('opened_at', '<=', $this->endDate))
            ->orderByDesc('opened_at')
            ->paginate($this->perPage);

        return view('livewire.manage-cash-drawer', [
            'openSession' => $openSession,
            'liveSummary' => $liveSummary,
            'sessions' => $sessions,
        ]);
    }
}
