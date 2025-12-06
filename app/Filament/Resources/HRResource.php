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
                //
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
            'index' => Pages\ListHRs::route('/'),
            'create' => Pages\CreateHR::route('/create'),
            'edit' => Pages\EditHR::route('/{record}/edit'),
        ];
    }
}

