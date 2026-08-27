@if($kpiName && ($fromDate || $toDate || $reportDate))
    <div wire:key="kpi-report-{{ $kpiName }}-{{ $fromDate }}-{{ $toDate }}-{{ $reportDate }}">
        @livewire('kpi-data-report-table', [
            'kpiName' => $kpiName,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'reportDate' => $reportDate,
            'value' => $value
        ])
    </div>
@else
    <p class="text-gray-500">Please select a KPI report name and date range to view details.</p>
@endif
