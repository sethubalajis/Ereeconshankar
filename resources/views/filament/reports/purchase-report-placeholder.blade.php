@if($branchId || $fromDate || $toDate || $reportDate)
    <div wire:key="purchase-report-{{ $branchId }}-{{ $fromDate }}-{{ $toDate }}-{{ $reportDate }}">
        @livewire('purchase-data-report-table', [
            'branchId' => $branchId,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'reportDate' => $reportDate
        ])
    </div>
@else
    <p class="text-gray-500">Please select a branch and date range to view purchase records.</p>
@endif
