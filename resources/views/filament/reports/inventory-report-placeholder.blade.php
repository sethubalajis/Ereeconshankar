@if($branchId || $fromDate || $toDate || $reportDate)
    <div wire:key="inventory-report-{{ $branchId }}-{{ $fromDate }}-{{ $toDate }}-{{ $reportDate }}">
        @livewire('inventory-data-report-table', [
            'branchId' => $branchId,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'reportDate' => $reportDate
        ])
    </div>
@else
    <p class="text-gray-500">Please select a branch and date range to view inventory details.</p>
@endif
