@if($branchId || $fromDate || $toDate || $reportMonth)
    <div wire:key="branch-performance-report-{{ $branchId }}-{{ $fromDate }}-{{ $toDate }}-{{ $reportMonth }}">
        @livewire('branch-performance-data-report-table', [
            'branchId' => $branchId,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'reportMonth' => $reportMonth
        ])
    </div>
@else
    <p class="text-gray-500">Please select a branch and date range to view performance details.</p>
@endif
