<?php

namespace App\Filament\Branch\Pages;

use App\Models\Branch;
use App\Models\BranchSetting;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Pages\Page;
use Filament\Notifications\Notification;

class RegionalSettingsPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-globe-alt';
    protected static \UnitEnum|string|null $navigationGroup = 'Settings';
    protected static ?string $title = 'Regional / Date & Time Settings';
    protected string $view = 'filament.branch.pages.regional-settings';

    public ?array $data = [];

    protected function getBranchId(): ?int
    {
        $branchId = session('branch_id');
        if (!$branchId && auth()->check()) {
            $branchId = auth()->user()->branch_id ?? auth()->user()->branches()->value('branches.id');
        }
        if (!$branchId) {
            try {
                $branchId = \Filament\Facades\Filament::getTenant()?->getKey();
            } catch (\Throwable $e) {}
        }
        if (!$branchId) {
            $branchId = Branch::query()->value('id');
        }
        return $branchId;
    }

    public function mount(): void
    {
        $branchId = $this->getBranchId();
        if ($branchId) {
            $settings = BranchSetting::where('branch_id', $branchId)->pluck('setting_value', 'setting_key')->toArray();
            $this->form->fill($settings);
        } else {
            $this->form->fill();
        }
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Branch Regional Settings (Overrides Company Settings)')
                    ->schema([
                        Select::make('date_format')
                            ->label('Date Format')
                            ->options([
                                'd/m/Y' => 'DD/MM/YYYY',
                                'm/d/Y' => 'MM/DD/YYYY',
                                'Y-m-d' => 'YYYY-MM-DD',
                                'd-m-Y' => 'DD-MM-YYYY',
                                'd.m.Y' => 'DD.MM.YYYY',
                            ]),
                        Select::make('time_format')
                            ->label('Time Format')
                            ->options([
                                'h:i A' => '12 Hour (AM/PM)',
                                'H:i' => '24 Hour',
                            ]),
                        Select::make('datetime_format')
                            ->label('Date-Time Format')
                            ->options([
                                'd/m/Y h:i A' => 'DD/MM/YYYY hh:mm A',
                                'm/d/Y h:i A' => 'MM/DD/YYYY hh:mm A',
                                'Y-m-d H:i' => 'YYYY-MM-DD HH:mm',
                                'd-m-Y h:i A' => 'DD-MM-YYYY hh:mm A',
                                'd.m.Y h:i A' => 'DD.MM.YYYY hh:mm A',
                            ]),
                        Select::make('timezone')
                            ->label('Time Zone')
                            ->options([
                                'Asia/Kolkata' => 'Asia/Kolkata',
                                'Asia/Dubai' => 'Asia/Dubai',
                                'Asia/Singapore' => 'Asia/Singapore',
                                'Europe/London' => 'Europe/London',
                                'America/New_York' => 'America/New_York',
                                'UTC' => 'UTC',
                            ])
                            ->searchable(),
                        Select::make('first_day_of_week')
                            ->label('First Day of Week')
                            ->options([
                                '0' => 'Sunday',
                                '1' => 'Monday',
                            ]),
                    ])->columns(2)
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $branchId = $this->getBranchId();

        if ($branchId) {
            foreach ($data as $key => $value) {
                if ($value !== null && $value !== '') {
                    BranchSetting::updateOrCreate(
                        ['branch_id' => $branchId, 'setting_key' => $key],
                        ['setting_value' => $value]
                    );
                } else {
                    BranchSetting::where('branch_id', $branchId)->where('setting_key', $key)->delete();
                }
            }

            Notification::make()
                ->title('Branch Settings saved successfully')
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('No active branch found to save settings.')
                ->danger()
                ->send();
        }
    }
}