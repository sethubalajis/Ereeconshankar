<?php

namespace App\Filament\Resources\Roles\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Schema as SchemaFacade;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        $components = [
            TextInput::make('name')
                ->required(),
        ];

        if (SchemaFacade::hasColumn('roles', 'description')) {
            $components[] = Textarea::make('description')
                ->columnSpanFull();
        }

        if (SchemaFacade::hasColumn('roles', 'status')) {
            $components[] = Toggle::make('status')
                ->required();
        }

        if (SchemaFacade::hasColumn('roles', 'guard_name')) {
            $components[] = TextInput::make('guard_name')
                ->default('web')
                ->required();
        }

        return $schema
            ->components($components);
    }
}
