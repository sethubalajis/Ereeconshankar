@if($reportType)
    <div wire:key="financial-report-{{ $reportType }}-{{ $fromDate }}-{{ $toDate }}-{{ $asOfDate }}">
        @livewire('financial-data-report-table', [
            'reportType' => $reportType,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'asOfDate' => $asOfDate
        ])
    </div>
@else
    <p class="text-gray-500">Please select a report type and date range to view financial details.</p>
@endif
