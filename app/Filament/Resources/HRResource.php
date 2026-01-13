<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HRResource\Pages;
use App\Models\HR;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class HRResource extends Resource
{
    protected static ?string $model = HR::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationGroup = null;
    
    public static function getNavigationGroup(): ?string
    {
        return __('app.user_management');
    }

    protected static ?string $navigationLabel = null;
    
    public static function getNavigationLabel(): string
    {
        return __('app.hrs');
    }
    
    public static function getModelLabel(): string
    {
        return __('app.hr');
    }
    
    public static function getPluralModelLabel(): string
    {
        return __('app.hrs');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('app.user_information'))
                    ->schema([
                        Forms\Components\TextInput::make('user_name')
                            ->label(__('common.name'))
                            ->required()
                            ->maxLength(255)
                            ->visible(fn (string $context): bool => $context === 'create'),
                        Forms\Components\TextInput::make('user_email')
                            ->label(__('app.email_address'))
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique('users', 'email')
                            ->visible(fn (string $context): bool => $context === 'create'),
                        Forms\Components\TextInput::make('user_password')
                            ->label(__('common.password'))
                            ->password()
                            ->required()
                            ->maxLength(255)
                            ->visible(fn (string $context): bool => $context === 'create'),
                        Forms\Components\Select::make('user_id')
                            ->label(__('app.user'))
                            ->relationship('user', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->disabled()
                            ->visible(fn (string $context): bool => $context === 'edit'),
                    ])
                    ->columns(2),
                Forms\Components\Section::make(__('app.hr_information'))
                    ->schema([
                        Forms\Components\TextInput::make('company_name')
                            ->label(__('app.company_name'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('position')
                            ->label(__('app.position'))
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->label(__('app.phone_number'))
                            ->tel()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('location')
                            ->label(__('common.location'))
                            ->maxLength(255),
                        Forms\Components\FileUpload::make('company_logo')
                            ->label(__('app.company_logo'))
                            ->image()
                            ->directory('company-logos')
                            ->visibility('public'),
                        Forms\Components\Textarea::make('bio')
                            ->label(__('app.bio'))
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with('user'))
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('common.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.email')
                    ->label(__('app.email_address'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('company_name')
                    ->label(__('app.company_name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('position')
                    ->label(__('app.position'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label(__('app.phone_number'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('location')
                    ->label(__('common.location'))
                    ->searchable(),
                Tables\Columns\ImageColumn::make('company_logo')
                    ->label(__('app.company_logo'))
                    ->circular(),
                Tables\Columns\TextColumn::make('jobs_count')
                    ->counts('jobs')
                    ->label(__('app.jobs_count'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('company_name')
                    ->label(__('app.company_name'))
                    ->form([
                        Forms\Components\TextInput::make('company_name')
                            ->label(__('app.company_name'))
                            ->placeholder(__('app.search_company')),
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when(
                            $data['company_name'],
                            fn ($query, $name) => $query->where('company_name', 'like', "%{$name}%")
                        );
                    }),
                Tables\Filters\Filter::make('position')
                    ->label(__('app.position'))
                    ->form([
                        Forms\Components\TextInput::make('position')
                            ->label(__('app.position'))
                            ->placeholder(__('app.search_position')),
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when(
                            $data['position'],
                            fn ($query, $position) => $query->where('position', 'like', "%{$position}%")
                        );
                    }),
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
                Tables\Filters\Filter::make('has_logo')
                    ->label(__('app.has_company_logo'))
                    ->query(fn ($query) => $query->whereNotNull('company_logo'))
                    ->toggle(),
                Tables\Filters\Filter::make('has_jobs')
                    ->label(__('app.has_jobs'))
                    ->query(fn ($query) => $query->has('jobs'))
                    ->toggle(),
                Tables\Filters\Filter::make('jobs_count')
                    ->label(__('app.jobs_count'))
                    ->form([
                        Forms\Components\TextInput::make('jobs_from')
                            ->label(__('app.min_jobs'))
                            ->numeric()
                            ->placeholder('0'),
                        Forms\Components\TextInput::make('jobs_to')
                            ->label(__('app.max_jobs'))
                            ->numeric()
                            ->placeholder('100'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['jobs_from'],
                                fn ($query, $count) => $query->has('jobs', '>=', $count)
                            )
                            ->when(
                                $data['jobs_to'],
                                fn ($query, $count) => $query->has('jobs', '<=', $count)
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
                        
                        if (!$user || !$user->isCandidate()) {
                            \Filament\Notifications\Notification::make()
                                ->title('Error')
                                ->body('Only candidates can chat with HR.')
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
                                ->body('HR user not found.')
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
                    ->visible(fn () => auth()->user()?->isCandidate()),
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
            'index' => Pages\ListHRs::route('/'),
            'create' => Pages\CreateHR::route('/create'),
            'edit' => Pages\EditHR::route('/{record}/edit'),
        ];
    }
}

