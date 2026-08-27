@if($reportType || $periodStart || $periodEnd)
    <div wire:key="gst-report-{{ $reportType }}-{{ $periodStart }}-{{ $periodEnd }}">
        @livewire('gst-data-report-table', [
            'reportType' => $reportType,
            'periodStart' => $periodStart,
            'periodEnd' => $periodEnd
        ])
    </div>
@else
    <p class="text-gray-500">Please select report type and period date range to view GST details.</p>
@endif
