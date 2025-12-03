<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CandidateResource\Pages;
use App\Models\Candidate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CandidateResource extends Resource
{
    protected static ?string $model = Candidate::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $navigationGroup = 'User Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->disabled(fn (string $context): bool => $context === 'create'),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('location')
                    ->maxLength(255),
                Forms\Components\FileUpload::make('resume_path')
                    ->directory('resumes')
                    ->visibility('public')
                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']),
                Forms\Components\Select::make('education_level')
                    ->options([
                        'high_school' => 'High School',
                        'diploma' => 'Diploma',
                        'bachelor' => 'Bachelor',
                        'master' => 'Master',
                        'phd' => 'PhD',
                    ]),
                Forms\Components\TextInput::make('years_of_experience')
                    ->numeric()
                    ->minValue(0),
                Forms\Components\TagsInput::make('skills')
                    ->placeholder('Add a skill and press Enter'),
                Forms\Components\Textarea::make('certifications')
                    ->rows(2),
                Forms\Components\Textarea::make('bio')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('location')
                    ->searchable(),
                Tables\Columns\TextColumn::make('education_level')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('years_of_experience')
                    ->sortable(),
                Tables\Columns\TextColumn::make('score')
                    ->label('CV Score')
                    ->numeric(
                        decimalPlaces: 1,
                    )
                    ->sortable()
                    ->color(fn ($state) => match (true) {
                        $state >= 8 => 'success',
                        $state >= 6 => 'warning',
                        $state >= 4 => 'info',
                        default => 'gray',
                    })
                    ->icon(fn ($state) => match (true) {
                        $state >= 8 => 'heroicon-o-star',
                        $state >= 6 => 'heroicon-o-check-circle',
                        default => 'heroicon-o-x-circle',
                    }),
                Tables\Columns\TextColumn::make('resume_path')
                    ->label('CV/Resume')
                    ->formatStateUsing(function ($state) {
                        return $state ? 'View CV' : 'No CV';
                    })
                    ->icon(fn ($state) => $state ? 'heroicon-o-document-text' : 'heroicon-o-x-circle')
                    ->color(fn ($state) => $state ? 'success' : 'gray')
                    ->url(fn ($record) => $record->resume_path ? asset('storage/' . $record->resume_path) : null)
                    ->openUrlInNewTab()
                    ->sortable(),
                Tables\Columns\TextColumn::make('applications_count')
                    ->counts('applications')
                    ->label('Applications')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('education_level')
                    ->options([
                        'high_school' => 'High School',
                        'diploma' => 'Diploma',
                        'bachelor' => 'Bachelor',
                        'master' => 'Master',
                        'phd' => 'PhD',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCandidates::route('/'),
            'edit' => Pages\EditCandidate::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false; // Candidates register through their own registration page
    }
}

