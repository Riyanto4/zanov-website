<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Http\Request;

class TransactionsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Ambil parameter filter dari request
        $status = $this->request->input('status');
        $paymentMethod = $this->request->input('payment_method');
        $dateFrom = $this->request->input('date_from');
        $dateTo = $this->request->input('date_to');
        $search = $this->request->input('search');
        $sortBy = $this->request->input('sort_by', 'created_at');
        $sortOrder = $this->request->input('sort_order', 'desc');

        // Query dasar dengan eager loading
        $query = Transaction::with(['items.product', 'user']);

        // Filter berdasarkan status
        if ($status && $status !== 'all') {
            $query->where('payment_status', $status);
        }

        // Filter berdasarkan metode pembayaran
        if ($paymentMethod && $paymentMethod !== 'all') {
            $query->where('payment_method', $paymentMethod);
        }

        // Filter berdasarkan tanggal
        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        // Filter berdasarkan pencarian (reference_no atau nama customer)
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('reference_no', 'like', "%{$search}%")
                ->orWhere('name', 'like', "%{$search}%")
                ->orWhereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('email', 'like', "%{$search}%");
                });
            });
        }

        // Sorting
        $validSortColumns = ['created_at', 'total_amount', 'payment_status', 'reference_no'];
        $sortBy = in_array($sortBy, $validSortColumns) ? $sortBy : 'created_at';
        $sortOrder = $sortOrder === 'asc' ? 'asc' : 'desc';
        
        $query->orderBy($sortBy, $sortOrder);

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Reference No',
            'Customer Name',
            'Customer Email',
            'Address',
            'Payment Method',
            'Payment Status',
            'Total Amount',
            'Items Count',
            'Notes',
            'Created At',
            'Verified At',
        ];
    }

    public function map($transaction): array
    {
        $paymentLabels = [
            'CASH' => 'Tunai',
            'TRANSFER' => 'Transfer',
            'QRIS' => 'QRIS',
            'COD' => 'COD',
        ];

        $statusLabels = [
            'PAID' => 'Lunas',
            'PENDING' => 'Menunggu',
            'CANCELED' => 'Dibatalkan',
        ];

        return [
            $transaction->reference_no,
            $transaction->name,
            $transaction->user ? $transaction->user->email : 'N/A',
            $transaction->address,
            $paymentLabels[$transaction->payment_method] ?? $transaction->payment_method,
            $statusLabels[$transaction->payment_status] ?? $transaction->payment_status,
            'Rp ' . number_format($transaction->total_amount, 2),
            $transaction->items->count() . ' items',
            $transaction->notes ?? '',
            $transaction->created_at->format('Y-m-d H:i:s'),
            $transaction->verified_at ? (is_string($transaction->verified_at) ? $transaction->verified_at : $transaction->verified_at->format('Y-m-d H:i:s')) : 'N/A',
        ];
    }
}
