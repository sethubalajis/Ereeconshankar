@if($branchId || $fromDate || $toDate || $reportDate)
    <div wire:key="sales-report-{{ $branchId }}-{{ $fromDate }}-{{ $toDate }}-{{ $reportDate }}">
        @livewire('sales-data-report-table', [
            'branchId' => $branchId,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'reportDate' => $reportDate
        ])
    </div>
@else
    <p class="text-gray-500">Please select a branch and date range to view sales.</p>
@endif
