<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Applications\Pages\ListApplications;
use App\Filament\Resources\Applications\Pages\ViewApplication;
use App\Filament\Resources\Applications\Tables\ApplicationsTable;
use App\Models\Application;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class ApplicationsResource extends Resource
{
    protected static ?string $model = Application::class;

    protected static BackedEnum|string|null $navigationIcon = Heroicon::ClipboardDocumentList;

    protected static UnitEnum|string|null $navigationGroup = 'Recruitment';

    public static function getLabel(): ?string
    {
        return __('Application');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Applications');
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return __('Recruitment');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return ApplicationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListApplications::route('/'),
            'view' => ViewApplication::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['job', 'candidate', 'currentAnalysis', 'latestReview']);
    }
}
