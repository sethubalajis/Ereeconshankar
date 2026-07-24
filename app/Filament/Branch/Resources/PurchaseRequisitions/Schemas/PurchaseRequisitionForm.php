<?php

namespace App\Filament\Branch\Resources\PurchaseRequisitions\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PurchaseRequisitionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('branch_id')->relationship('branch', 'name')
                    ->required(),
                \Filament\Forms\Components\Select::make('department_id')->relationship('department', 'name'),
                TextInput::make('requisition_number')
                    ->required()
                    ->default(function (): string {
                        $year = now()->format('Y');
                        $prefix = "PR-{$year}-";
                        $latest = \App\Models\PurchaseRequisition::query()
                            ->where('requisition_number', 'like', $prefix . '%')
                            ->orderByDesc('id')
                            ->value('requisition_number');

                        $next = 1;

                        if ($latest) {
                            $next = ((int) substr($latest, strlen($prefix))) + 1;
                        }

                        return $prefix . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
                    })
                    ->readOnly(),
                \Filament\Forms\Components\Select::make('subject')
                    ->options(function () {
                        $defaults = [
                            'Office Supplies Request' => 'Office Supplies Request',
                            'IT Equipment Purchase' => 'IT Equipment Purchase',
                            'New Server Requisition' => 'New Server Requisition',
                            'Raw Materials for Production' => 'Raw Materials for Production',
                            'Marketing Materials' => 'Marketing Materials',
                            'Furniture Replacements' => 'Furniture Replacements',
                        ];
                        
                        $existing = \App\Models\PurchaseRequisition::query()
                            ->whereNotNull('subject')
                            ->distinct()
                            ->pluck('subject', 'subject')
                            ->toArray();
                            
                        return array_merge($defaults, $existing);
                    })
                    ->searchable()
                    ->createOptionForm([
                        \Filament\Forms\Components\TextInput::make('subject')
                            ->required()
                            ->label('New Subject'),
                    ])
                    ->createOptionUsing(function (array $data) {
                        return $data['subject'];
                    }),
                DatePicker::make('required_date')
                    ->required()
                    ->default(now()->timezone('Asia/Kolkata')),
                \Filament\Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->required()
                    ->default('pending'),
                Textarea::make('remarks')
                    ->columnSpanFull(),
            ]);
    }
}
