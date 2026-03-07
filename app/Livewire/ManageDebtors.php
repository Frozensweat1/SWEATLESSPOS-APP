<?php

namespace App\Livewire;

use App\Models\Sale;
use App\Models\Payment;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Traits\StoreScoped;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.app')]
class ManageDebtors extends Component
{
    use WithPagination, StoreScoped;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $filterStatus = '';
    public $pageTitle = 'Debtors Management';
    public $componentName = 'Debtors';
    public $selected_id;
    public $paymentAmount = 0;
    public $paymentMethod = 'cash';
    public $showPaymentModal = false;
    public $selectedSale = null;

    public function __construct()
    {
        $this->initStoreId();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function getDebtorsQuery()
    {
        return Sale::where('store_id', $this->getStoreId())
            ->whereIn('payment_status', ['pending', 'partial'])
            ->when($this->search, function ($query) {
                $query->whereHas('customer', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('phone', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterStatus, function ($query) {
                $query->where('payment_status', $this->filterStatus);
            })
            ->with('customer')
            ->orderBy('due_date', 'asc');
    }

    public function render()
    {
        $debtors = $this->getDebtorsQuery()->paginate(10);

        return view('livewire.manage-debtors', [
            'debtors' => $debtors,
        ]);
    }

    public function recordPayment($saleId)
    {
        $this->selected_id = $saleId;
        $this->selectedSale = Sale::where('store_id', $this->getStoreId())->find($saleId);
        $this->paymentAmount = 0;
        $this->paymentMethod = 'cash';
        $this->showPaymentModal = true;
    }

    public function closePaymentModal()
    {
        $this->showPaymentModal = false;
        $this->reset('paymentAmount', 'paymentMethod', 'selected_id', 'selectedSale');
    }

    public function confirmPayment()
    {
        $sale = Sale::where('store_id', $this->getStoreId())->find($this->selected_id);

        if (!$sale || $this->paymentAmount <= 0) {
            LivewireAlert::title('Error')
                ->text('Please enter a valid payment amount.')
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }

        $newAmountDue = $sale->amount_due - $this->paymentAmount;

        if ($newAmountDue < 0) {
            LivewireAlert::title('Error')
                ->text('Payment amount exceeds the amount due.')
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }

        // Update sale payment status
        $sale->update([
            'amount_paid' => $sale->amount_paid + $this->paymentAmount,
            'amount_due' => $newAmountDue,
            'payment_status' => $newAmountDue == 0 ? 'paid' : 'partial',
        ]);

        // Log notification
        $this->logNotification(
            'Payment Recorded',
            "Payment of \$" . number_format($this->paymentAmount, 2) . " recorded for {$sale->customer->name}"
        );

        LivewireAlert::title('Success')
            ->text('Payment recorded successfully!')
            ->success()
            ->toast()
            ->position('top-end')
            ->show();

        $this->reset('paymentAmount', 'paymentMethod', 'selected_id');
    }

    protected function logNotification($title, $message)
    {
        $authUser = Auth::user();

        \App\Models\Notification::create([
            'user_id' => $authUser->id,
            'title' => $title,
            'message' => $message . " Action by {$authUser->name} (ID: {$authUser->id}).",
            'type' => 'info',
            'is_read' => false,
        ]);
    }
}
