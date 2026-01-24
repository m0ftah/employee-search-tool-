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

    protected static ?string $navigationGroup = null;
    
    public static function getNavigationGroup(): ?string
    {
        return __('app.user_management');
    }
    
    protected static ?string $navigationLabel = null;
    
    public static function getNavigationLabel(): string
    {
        return __('app.candidates');
    }
    
    public static function getModelLabel(): string
    {
        return __('app.candidate');
    }
    
    public static function getPluralModelLabel(): string
    {
        return __('app.candidates');
    }

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
                    ->label(__('app.phone_number'))
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('location')
                    ->label(__('common.location'))
                    ->maxLength(255),
                Forms\Components\FileUpload::make('resume_path')
                    ->label(__('app.resume'))
                    ->directory('resumes')
                    ->visibility('public')
                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']),
                Forms\Components\Select::make('education_level')
                    ->label(__('app.education_level'))
                    ->options([
                        'high_school' => __('app.high_school'),
                        'diploma' => __('app.diploma'),
                        'bachelor' => __('app.bachelor'),
                        'master' => __('app.master'),
                        'phd' => __('app.phd'),
                    ]),
                Forms\Components\TextInput::make('years_of_experience')
                    ->label(__('app.years_of_experience'))
                    ->numeric()
                    ->minValue(0),
                Forms\Components\TagsInput::make('skills')
                    ->label(__('app.skills'))
                    ->placeholder(__('app.add_skill_placeholder')),
                Forms\Components\Textarea::make('certifications')
                    ->label(__('app.certifications'))
                    ->rows(2),
                Forms\Components\Textarea::make('bio')
                    ->label(__('app.bio'))
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with('user'))
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('common.name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.email')
                    ->label(__('app.email_address'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label(__('app.phone_number'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('location')
                    ->label(__('common.location'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('education_level')
                    ->label(__('app.education_level'))
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'high_school' => __('app.high_school'),
                        'diploma' => __('app.diploma'),
                        'bachelor' => __('app.bachelor'),
                        'master' => __('app.master'),
                        'phd' => __('app.phd'),
                        default => $state ?? '',
                    })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('years_of_experience')
                    ->label(__('app.years_of_experience'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('score')
                    ->label(__('app.cv_score'))
                    ->numeric(
                        decimalPlaces: 0,
                    )
                    ->badge()
                    ->sortable()
                    ->color(fn ($state) => match (true) {
                        $state >= 8 => 'success',
                        $state >= 6 => 'warning',
                        $state >= 4 => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('resume_path')
                    ->label(__('app.cv_resume'))
                    ->formatStateUsing(function ($state) {
                        return $state ? __('app.view_cv') : __('app.no_cv');
                    })
                    ->icon(fn ($state) => $state ? 'heroicon-o-document-text' : 'heroicon-o-x-circle')
                    ->color(fn ($state) => $state ? 'success' : 'gray')
                    ->url(fn ($record) => $record->resume_path ? asset('storage/' . $record->resume_path) : null)
                    ->openUrlInNewTab()
                    ->sortable(),
                Tables\Columns\TextColumn::make('applications_count')
                    ->counts('applications')
                    ->label(__('app.applications'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('education_level')
                    ->label(__('app.education_level'))
                    ->options([
                        'high_school' => __('app.high_school'),
                        'diploma' => __('app.diploma'),
                        'bachelor' => __('app.bachelor'),
                        'master' => __('app.master'),
                        'phd' => __('app.phd'),
                    ])
                    ->multiple(),
                Tables\Filters\Filter::make('score')
                    ->label(__('app.cv_score'))
                    ->form([
                        Forms\Components\TextInput::make('score_from')
                            ->label(__('app.min_score'))
                            ->numeric()
                            ->placeholder('0'),
                        Forms\Components\TextInput::make('score_to')
                            ->label(__('app.max_score'))
                            ->numeric()
                            ->placeholder('10'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['score_from'],
                                fn ($query, $score) => $query->where('score', '>=', $score)
                            )
                            ->when(
                                $data['score_to'],
                                fn ($query, $score) => $query->where('score', '<=', $score)
                            );
                    }),
                Tables\Filters\Filter::make('years_of_experience')
                    ->label(__('app.years_of_experience'))
                    ->form([
                        Forms\Components\TextInput::make('experience_from')
                            ->label(__('app.min_years'))
                            ->numeric()
                            ->placeholder('0'),
                        Forms\Components\TextInput::make('experience_to')
                            ->label(__('app.max_years'))
                            ->numeric()
                            ->placeholder('50'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['experience_from'],
                                fn ($query, $years) => $query->where('years_of_experience', '>=', $years)
                            )
                            ->when(
                                $data['experience_to'],
                                fn ($query, $years) => $query->where('years_of_experience', '<=', $years)
                            );
                    }),
                Tables\Filters\Filter::make('has_resume')
                    ->label(__('app.has_resume'))
                    ->query(fn ($query) => $query->whereNotNull('resume_path'))
                    ->toggle(),
                Tables\Filters\Filter::make('has_applications')
                    ->label(__('app.has_applications'))
                    ->query(fn ($query) => $query->has('applications'))
                    ->toggle(),
                Tables\Filters\Filter::make('location')
                    ->label(__('common.location'))
                    ->form([
                        Forms\Components\TextInput::make('location')
                            ->label(__('common.location'))
                            ->placeholder(__('app.search_location')),
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when(
                            $data['location'],
                            fn ($query, $location) => $query->where('location', 'like', "%{$location}%")
                        );
                    }),
                Tables\Filters\Filter::make('phone')
                    ->label(__('app.phone_number'))
                    ->form([
                        Forms\Components\TextInput::make('phone')
                            ->label(__('app.phone_number'))
                            ->placeholder(__('app.search_phone')),
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when(
                            $data['phone'],
                            fn ($query, $phone) => $query->where('phone', 'like', "%{$phone}%")
                        );
                    }),
                Tables\Filters\Filter::make('created_at')
                    ->label(__('app.created_at'))
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label(__('app.from_date')),
                        Forms\Components\DatePicker::make('created_to')
                            ->label(__('app.to_date')),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn ($query, $date) => $query->whereDate('created_at', '>=', $date)
                            )
                            ->when(
                                $data['created_to'],
                                fn ($query, $date) => $query->whereDate('created_at', '<=', $date)
                            );
                    }),
            ], layout: Tables\Enums\FiltersLayout::AboveContentCollapsible)
            ->actions([
                Tables\Actions\Action::make('chat')
                    ->label(__('app.chat'))
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('info')
                    ->action(function ($record) {
                        $user = auth()->user();
                        
                        if (!$user || !$user->isHR()) {
                            \Filament\Notifications\Notification::make()
                                ->title('Error')
                                ->body('Only HR can chat with candidates.')
                                ->danger()
                                ->send();
                            return;
                        }
                        
                        // Load user relationship
                        if (!$record->relationLoaded('user')) {
                            $record->load('user');
                        }
                        
                        if (!$record->user) {
                            \Filament\Notifications\Notification::make()
                                ->title('Error')
                                ->body('Candidate user not found.')
                                ->danger()
                                ->send();
                            return;
                        }
                        
                        try {
                            // Create or get conversation
                            $conversation = $user->createConversationWith($record->user);
                            
                            if (!$conversation) {
                                throw new \Exception('Failed to create conversation.');
                            }
                            
                            $prefix = config('wirechat.routes.prefix', 'chats');
                            $chatUrl = url("/{$prefix}/{$conversation->id}");
                            
                            \Filament\Notifications\Notification::make()
                                ->success()
                                ->title('Chat Created')
                                ->body('Opening chat with ' . $record->user->name)
                                ->send();
                            
                            // Redirect to chat
                            return redirect($chatUrl);
                        } catch (\Exception $e) {
                            \Filament\Notifications\Notification::make()
                                ->title('Error Creating Chat')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    })
                    ->visible(fn () => auth()->user()?->isHR()),
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

