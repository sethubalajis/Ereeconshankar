<div>
    @if(isset($summary))
    <div class="mb-6 p-4 bg-white rounded-lg shadow dark:bg-gray-800">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold">Report Summary</h3>
            <div class="flex gap-2">
                <button wire:click="exportToCsv" class="px-3 py-1.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg hover:bg-emerald-100 flex items-center gap-2 text-sm font-medium transition-colors">
                    Export CSV
                </button>
                <button wire:click="exportToPdf" class="px-3 py-1.5 bg-rose-50 text-rose-700 border border-rose-200 rounded-lg hover:bg-rose-100 flex items-center gap-2 text-sm font-medium transition-colors">
                    Export PDF
                </button>
                <button onclick="window.print()" class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 flex items-center gap-2 text-sm font-medium transition-colors">
                    Print
                </button>
            </div>
        </div>
    </div>
    @endif

    {{ $this->table }}
</div>
