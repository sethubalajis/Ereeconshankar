<?php

namespace App\Filament\Branch\Resources\Attendances\Pages;

use App\Filament\Branch\Resources\Attendances\AttendanceResource;
use App\Services\BiometricAttendanceImporter;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListAttendances extends ListRecords
{
    protected static string $resource = AttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('uploadBiometricFile')
                ->label('Upload Biometric File')
                ->icon('heroicon-o-arrow-up-tray')
                ->form([
                    FileUpload::make('biometric_file')
                        ->label('Biometric File')
                        ->disk('local')
                        ->directory('private/biometric')
                        ->helperText('Upload biometric device file in .dat, .txt, or .csv format.')
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $storedPath = is_array($data['biometric_file'] ?? null)
                        ? ($data['biometric_file'][0] ?? null)
                        : ($data['biometric_file'] ?? null);

                    $result = app(BiometricAttendanceImporter::class)->importFromStoredPath((string) $storedPath);

                    Notification::make()
                        ->title('Biometric attendance import completed')
                        ->body("Processed {$result['processed']} lines. Created {$result['created']}, updated {$result['updated']}, unmatched {$result['unmatched']}, skipped {$result['skipped']}.")
                        ->success()
                        ->send();
                }),
            CreateAction::make(),
        ];
    }
}
