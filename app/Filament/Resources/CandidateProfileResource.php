<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CandidateProfileResource\Pages;
use App\Filament\Resources\CandidateProfileResource\RelationManagers;
use App\Models\CandidateProfile;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CandidateProfileResource extends Resource
{
    protected static ?string $model = CandidateProfile::class;
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'Candidate Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Personal Information')
                    ->schema([
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('address')
                            ->rows(3)
                            ->maxLength(65535),
                        Forms\Components\TextInput::make('education_level')
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('Professional Information')
                    ->schema([
                        Forms\Components\TextInput::make('current_job_title')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('years_of_experience')
                            ->numeric()
                            ->minValue(0),
                        Forms\Components\TagsInput::make('skills')
                            ->placeholder('Add a skill')
                            ->suggestions([
                                'PHP', 'Laravel', 'JavaScript', 'Vue.js', 'React', 'MySQL',
                                'CSS', 'HTML', 'Git', 'Docker', 'AWS', 'Node.js'
                            ]),
                        Forms\Components\Textarea::make('bio')
                            ->rows(3)
                            ->maxLength(65535),
                    ])->columns(2),

                Forms\Components\Section::make('Resume')
                    ->schema([
                        Forms\Components\FileUpload::make('resume_path')
                            ->label('Upload Resume')
                            ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                            ->maxSize(5120) // 5MB
                            ->directory('resumes')
                            ->visibility('private'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('current_job_title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('education_level')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('years_of_experience')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('resume_path')
                    ->label('Resume')
                    ->boolean(),
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
                Tables\Filters\Filter::make('has_resume')
                    ->label('Has Resume')
                    ->query(fn (Builder $query) => $query->whereNotNull('resume_path')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

//    public static function getRelations(): array
//    {
//        return [
//            RelationManagers\ApplicationsRelationManager::class,
//        ];
//    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCandidateProfiles::route('/'),
            'create' => Pages\CreateCandidateProfile::route('/create'),
            'edit' => Pages\EditCandidateProfile::route('/{record}/edit'),
//            'view' => Pages\ViewCandidateProfile::route('/{record}'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->hasRole('admin') || auth()->user()->hasRole('hr');
    }
    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasRole('candidate');
    }
}
