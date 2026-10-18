<?php

namespace App\Filament\Resources;

use App\Enums\ApplicationStatus;
use App\Enums\ReviewStatus;
use App\Filament\Resources\Applications\Pages\ListApplications;
use App\Filament\Resources\Applications\Pages\ViewApplication;
use App\Models\Application;
use App\Services\Ranking\CandidateRankingService;
use App\Services\Screening\ScreeningService;
use Filament\Actions\Action;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ApplicationsResource extends Resource
{
    protected static ?string $model = Application::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ClipboardDocumentList;

    protected static ?string $navigationGroup = 'Recruitment';

    public static function getLabel(): ?string
    {
        return 'Application';
    }

    public static function getPluralLabel(): ?string
    {
        return 'Applications';
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
