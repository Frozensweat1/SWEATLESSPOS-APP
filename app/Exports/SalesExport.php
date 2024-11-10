<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesExport implements FromCollection, WithHeadings
{
    protected $sales;

    public function __construct($sales)
    {
        $this->sales = $sales;
    }

    public function collection()
    {
        return collect($this->sales)->map(function ($sale) {
            return [
                'ID' => $sale->id,
                'Customer' => $sale->customer->name,
                'Product' => $sale->product->name,
                'Quantity' => $sale->quantity,
                'Total Amount' => $sale->total_amount,
                'Date' => $sale->created_at->format('Y-m-d'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Sale ID',
            'Customer Name',
            'Product Name',
            'Quantity',
            'Total Amount',
            'Date',
        ];
    }
}
