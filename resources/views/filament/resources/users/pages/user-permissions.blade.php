<x-filament-panels::page>
    <div class="mb-4">
        <strong>User:</strong> {{ $record->name }}
    </div>

    <div class="mb-6 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
        <label class="mb-2 block text-sm font-medium text-gray-700">Branch</label>
        <select wire:model.live="selectedBranchId" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
            <option value="">Select a branch</option>
            @foreach ($this->getBranchOptions() as $id => $name)
                <option value="{{ $id }}">{{ $name }}</option>
            @endforeach
        </select>
    </div>

    <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white shadow-sm" wire:key="user-matrix-{{ $this->matrixRenderKey }}">
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
                                @if (! $this->hasAction($module, $action))
                                    <span class="text-gray-300">-</span>
                                @elseif ($this->isInheritedAction($module, $action))
                                    <span class="inline-flex items-center justify-center text-green-600" style="color: #16a34a; font-size: 1rem;" aria-label="Role permission granted">✓</span>
                                @else
                                    <input
                                        type="checkbox"
                                        wire:key="user-checkbox-{{ $module }}-{{ $action }}-{{ $this->matrixRenderKey }}"
                                        wire:model.live="matrix.{{ $module }}.{{ $action }}"
                                        @checked($this->matrix[$module][$action] ?? false)
                                        class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                                    >
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-5 flex gap-3">
        <x-filament::button color="gray" wire:click="refreshMatrix">
            Refresh
        </x-filament::button>

        <x-filament::button wire:click="save">
            Save Permissions
        </x-filament::button>
    </div>
</x-filament-panels::page>