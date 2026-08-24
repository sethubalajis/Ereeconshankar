<?php

namespace App\Filament\Pages;

use App\Models\CompanySetting;
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
    protected string $view = 'filament.pages.regional-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $companyId = session('company_id') ?? (auth()->check() ? auth()->user()->company_id : null);
        if ($companyId) {
            $settings = CompanySetting::where('company_id', $companyId)->pluck('setting_value', 'setting_key')->toArray();
            $this->form->fill($settings);
        } else {
            $this->form->fill();
        }
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Regional Settings')
                    ->schema([
                        Select::make('date_format')
                            ->label('Date Format')
                            ->options([
                                'd/m/Y' => 'DD/MM/YYYY',
                                'm/d/Y' => 'MM/DD/YYYY',
                                'Y-m-d' => 'YYYY-MM-DD',
                                'd-m-Y' => 'DD-MM-YYYY',
                                'd.m.Y' => 'DD.MM.YYYY',
                            ])
                            ->required(),
                        Select::make('time_format')
                            ->label('Time Format')
                            ->options([
                                'h:i A' => '12 Hour (AM/PM)',
                                'H:i' => '24 Hour',
                            ])
                            ->required(),
                        Select::make('datetime_format')
                            ->label('Date-Time Format')
                            ->options([
                                'd/m/Y h:i A' => 'DD/MM/YYYY hh:mm A',
                                'm/d/Y h:i A' => 'MM/DD/YYYY hh:mm A',
                                'Y-m-d H:i' => 'YYYY-MM-DD HH:mm',
                                'd-m-Y h:i A' => 'DD-MM-YYYY hh:mm A',
                                'd.m.Y h:i A' => 'DD.MM.YYYY hh:mm A',
                            ])
                            ->required(),
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
                            ->searchable()
                            ->required(),
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
        $companyId = session('company_id') ?? (auth()->check() ? auth()->user()->company_id : null);

        if ($companyId) {
            foreach ($data as $key => $value) {
                CompanySetting::updateOrCreate(
                    ['company_id' => $companyId, 'setting_key' => $key],
                    ['setting_value' => $value]
                );
            }

            Notification::make()
                ->title('Settings saved successfully')
                ->success()
                ->send();
        }
    }
}
