<?php

namespace App\Exports;

use App\Models\TransferCoins;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class AccountExport implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithCustomStartCell, WithEvents
{
    protected $from;
    protected $to;
    protected $filter;
    protected $headerLabels = [
        'Id',
        'From User',
        'To User',
        'Type',
        'Coin Quantity',
        'Coin Type',
        'Remarks',
        'Created At',
        'Updated At'
    ];

    public function __construct($from, $to, $filter)
    {
        $this->from = $from;
        $this->to = $to;
        $this->filter = $filter;
    }

    public function query()
    {
        $id = auth()->user()->id;
        $query = TransferCoins::with(['fromUser', 'toUser']);

        if ($this->from && $this->to) {
            $fromDate = \Carbon\Carbon::parse($this->from)->startOfDay();
            $toDate = \Carbon\Carbon::parse($this->to)->endOfDay();
            $query->whereBetween('created_at', [$fromDate, $toDate]);
        }

        if ($this->filter && $this->filter != 'all') {
            $query->where('type', $this->filter);
        }

        if (!auth()->user()->hasRole('admin') && !auth()->user()->hasRole('employee')) {
            $query->where(function ($query) use ($id) {
                $query->where('from_user_id', $id)
                    ->orWhere('to_user_id', $id);
            });
        }

        return $query;
    }

    public function map($transaction): array
    {
        return [
            $transaction->id,
            $transaction->fromUser ? $transaction->fromUser->name : 'N/A',
            $transaction->toUser ? $transaction->toUser->name : 'N/A',
            $transaction->type,
            $transaction->coin_quantity,
            $transaction->coin_type,
            $transaction->remarks,
            $transaction->created_at->format('Y-m-d H:i:s'),
            $transaction->updated_at->format('Y-m-d H:i:s'),
        ];
    }

    public function headings(): array
    {
        return $this->headerLabels;
    }

    public function startCell(): string
    {
        return 'A1'; // Start output from A2 to allow space for "No records found"
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $queryResult = $this->query()->get();
                if ($queryResult->isEmpty()) {
                    $event->sheet->mergeCells('A1:I1');
                    $event->sheet->setCellValue('A1', 'No records found');
                    $event->sheet->getStyle('A1:I1')->getAlignment()->setHorizontal('center');
                }
            },
        ];
    }
}
