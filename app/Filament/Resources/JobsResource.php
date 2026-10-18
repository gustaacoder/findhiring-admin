<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Jobs\Schemas\JobForm;
use App\Filament\Resources\Jobs\Tables\JobsTable;
use App\Models\Job;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class JobsResource extends Resource
{
    protected static ?string $model = Job::class;

    protected static BackedEnum|string|null $navigationIcon = Heroicon::Briefcase;

    protected static UnitEnum|string|null $navigationGroup = 'Recruitment';

    public static function getLabel(): ?string
    {
        return 'Job';
    }

    public static function getPluralLabel(): ?string
    {
        return 'Jobs';
    }

    public static function form(Schema $schema): Schema
    {
        return JobForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JobsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\Jobs\RelationManagers\CriteriaRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Jobs\Pages\ListJobs::route('/'),
            'create' => Jobs\Pages\CreateJob::route('/create'),
            'edit' => Jobs\Pages\EditJob::route('/{record}/edit'),
            'view' => Jobs\Pages\ViewJob::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
