@if($branchId || $fromDate || $toDate)
    <div wire:key="stock-transaction-report-{{ $branchId }}-{{ $fromDate }}-{{ $toDate }}">
        @livewire('stock-transaction-data-report-table', [
            'branchId' => $branchId,
            'fromDate' => $fromDate,
            'toDate' => $toDate
        ])
    </div>
@else
    <p class="text-gray-500">Please select a branch and date range to view stock transactions.</p>
@endif
