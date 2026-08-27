@if($fromDate || $toDate || $reportMonth)
    <div wire:key="hr-report-{{ $branchId }}-{{ $fromDate }}-{{ $toDate }}-{{ $reportMonth }}">
        @livewire('hr-data-report-table', [
            'branchId' => $branchId,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'reportMonth' => $reportMonth
        ])
    </div>
@else
    <p class="text-gray-500">Please select a date range to view report details.</p>
@endif
