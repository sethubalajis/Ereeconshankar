<x-filament-panels::page>
    <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-gray-200 table-auto border border-gray-200" style="border-collapse: collapse;">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-700 border-b border-gray-200" style="padding: .75rem 1rem; border: 1px solid #e5e7eb;">Module</th>
                    @foreach ($this->getActionLabels() as $action => $label)
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide text-gray-700 border-b border-gray-200" style="padding: .75rem 1rem; border: 1px solid #e5e7eb;">{{ $label }}</th>
                    @endforeach
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 bg-white">
                @foreach ($modules as $module => $label)
                    <tr>
                        <td class="whitespace-nowrap px-4 py-3 font-medium text-gray-900" style="padding: .75rem 1rem; border: 1px solid #e5e7eb;">
                            {{ $label }}
                        </td>

                        @foreach ($this->getActionLabels() as $action => $actionLabel)
                            <td class="px-4 py-3 text-center" style="padding: .75rem 1rem; border: 1px solid #e5e7eb;">
                                @if ($this->hasAction($module, $action))
                                    <input
                                        type="checkbox"
                                        wire:model.live="matrix.{{ $module }}.{{ $action }}"
                                        class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                                    >
                                @else
                                    <span class="text-gray-300">-</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6 flex justify-end">
        <x-filament::button wire:click="save">
            Save Permissions
        </x-filament::button>
    </div>
</x-filament-panels::page>