<?php

namespace App\Filament\Resources\Permissions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Schema as SchemaFacade;

class PermissionForm
{
    public static function configure(Schema $schema): Schema
    {
        $components = [
            TextInput::make('name')
                ->required(),
        ];

        if (SchemaFacade::hasColumn('permissions', 'module')) {
            $components[] = TextInput::make('module');
        } elseif (SchemaFacade::hasColumn('permissions', 'guard_name')) {
            $components[] = TextInput::make('guard_name')
                ->default('web')
                ->required();
        }

        if (SchemaFacade::hasColumn('permissions', 'description')) {
            $components[] = Textarea::make('description')
                ->columnSpanFull();
        }

        return $schema
            ->components($components);
    }
}
