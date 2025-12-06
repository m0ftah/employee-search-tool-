<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApplicationResource\Pages;
use App\Models\Application;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ApplicationResource extends Resource
{
    protected static ?string $model = Application::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = null;
    
    public static function getNavigationGroup(): ?string
    {
        return __('app.jobs');
    }
    
    protected static ?string $navigationLabel = null;
    
    public static function getNavigationLabel(): string
    {
        return __('app.applications');
    }
    
    public static function getModelLabel(): string
    {
        return __('app.application');
    }
    
    public static function getPluralModelLabel(): string
    {
        return __('app.applications');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('job_id')
                    ->relationship('job', 'title', 
                        fn ($query) => auth()->user()->isHR() && auth()->user()->hr
                            ? $query->where('hr_id', auth()->user()->hr->id)
                            : $query
                    )
                    ->required()
                    ->searchable()
                    ->preload()
                    ->disabled(fn (string $context): bool => $context === 'edit' && auth()->user()->isCandidate()),
                Forms\Components\Select::make('candidate_id')
                    ->relationship('candidate', 'user.name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->visible(fn () => auth()->user()->isAdmin() || auth()->user()->isHR())
                    ->disabled(fn (string $context): bool => $context === 'edit'),
                Forms\Components\FileUpload::make('resume_path')
                    ->directory('application-resumes')
                    ->visibility('public')
                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => __('app.pending'),
                        'reviewed' => __('app.reviewed'),
                        'shortlisted' => __('app.shortlisted'),
                        'rejected' => __('app.rejected'),
                        'hired' => __('app.hired'),
                    ])
                    ->required()
                    ->default('pending'),
                Forms\Components\Textarea::make('feedback_from_hr')
                    ->label(__('app.hr_feedback'))
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('feedback_from_candidate')
                    ->label(__('app.candidate_feedback'))
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\DateTimePicker::make('applied_at')
                    ->default(now())
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('job.title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('job.hr.company_name')
                    ->label(__('app.company_name'))
                    ->searchable()
                    ->sortable()
                    ->visible(fn () => auth()->user()->isAdmin() || auth()->user()->isCandidate()),
                Tables\Columns\TextColumn::make('candidate.user.name')
                    ->label(__('app.candidate'))
                    ->searchable()
                    ->sortable()
                    ->visible(fn () => auth()->user()->isAdmin() || auth()->user()->isHR()),
                Tables\Columns\TextColumn::make('candidate.user.email')
                    ->label(__('app.email_address'))
                    ->searchable()
                    ->visible(fn () => auth()->user()->isAdmin() || auth()->user()->isHR()),
                Tables\Columns\TextColumn::make('candidate.score')
                    ->label(__('app.cv_score'))
                    ->numeric(
                        decimalPlaces: 1,
                    )
                    ->badge()
                    ->color(fn ($state): string => match (true) {
                        $state === null => 'gray',
                        $state >= 8 => 'success',
                        $state >= 6 => 'warning',
                        default => 'danger',
                    })
                    ->sortable()
                    ->visible(fn () => auth()->user()->isAdmin() || auth()->user()->isHR()),
                Tables\Columns\TextColumn::make('cv')
                    ->label(__('app.cv_resume'))
                    ->getStateUsing(function ($record) {
                        // First check if application has a resume
                        if ($record->resume_path) {
                            return __('app.view_cv');
                        }
                        // Fall back to candidate's profile resume
                        if ($record->candidate && $record->candidate->resume_path) {
                            return __('app.view_cv');
                        }
                        return __('app.no_cv');
                    })
                    ->icon(function ($state) {
                        return $state === __('app.view_cv') ? 'heroicon-o-document-text' : 'heroicon-o-x-circle';
                    })
                    ->color(function ($state) {
                        return $state === __('app.view_cv') ? 'success' : 'gray';
                    })
                    ->url(function ($record) {
                        // First check if application has a resume
                        if ($record->resume_path) {
                            return asset('storage/' . $record->resume_path);
                        }
                        // Fall back to candidate's profile resume
                        if ($record->candidate && $record->candidate->resume_path) {
                            return asset('storage/' . $record->candidate->resume_path);
                        }
                        return null;
                    })
                    ->openUrlInNewTab()
                    ->visible(fn () => auth()->user()->isAdmin() || auth()->user()->isHR())
                    ->sortable(false),
                    
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => __('app.pending'),
                        'reviewed' => __('app.reviewed'),
                        'shortlisted' => __('app.shortlisted'),
                        'rejected' => __('app.rejected'),
                        'hired' => __('app.hired'),
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'reviewed' => 'info',
                        'shortlisted' => 'warning',
                        'rejected' => 'danger',
                        'hired' => 'success',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('feedback_from_hr')
                    ->label(__('app.hr_feedback'))
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->feedback_from_hr)
                    ->visible(fn () => auth()->user()->isAdmin() || auth()->user()->isHR())
                    ->wrap(),
                Tables\Columns\TextColumn::make('applied_at')
                    ->label(__('app.applied_at'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => __('app.pending'),
                        'reviewed' => __('app.reviewed'),
                        'shortlisted' => __('app.shortlisted'),
                        'rejected' => __('app.rejected'),
                        'hired' => __('app.hired'),
                    ]),
                Tables\Filters\SelectFilter::make('job_id')
                    ->relationship('job', 'title')
                    ->label(__('app.job_title')),
            ])
            ->actions([
                Tables\Actions\Action::make('accept')
                    ->label(__('app.accept'))
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => auth()->user()->isHR() && in_array($record->status, ['pending', 'reviewed']))
                    ->requiresConfirmation()
                    ->modalHeading(__('app.accept_application'))
                    ->modalDescription(__('app.application_moved_to_shortlisted'))
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'shortlisted',
                        ]);

                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title(__('app.application_accepted'))
                            ->body(__('app.application_moved_to_shortlisted'))
                            ->send();
                    }),
                Tables\Actions\Action::make('hire')
                    ->label(__('app.hire'))
                    ->icon('heroicon-o-star')
                    ->color('success')
                    ->visible(fn ($record) => auth()->user()->isHR() && $record->status === 'shortlisted')
                    ->requiresConfirmation()
                    ->modalHeading(__('app.hire_candidate'))
                    ->modalDescription(__('app.candidate_marked_as_hired'))
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'hired',
                        ]);

                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title(__('app.candidate_hired'))
                            ->body(__('app.candidate_marked_as_hired'))
                            ->send();
                    }),
                Tables\Actions\Action::make('reject')
                    ->label(__('app.reject'))
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record) => auth()->user()->isHR() && in_array($record->status, ['pending', 'reviewed', 'shortlisted']))
                    ->form([
                        Forms\Components\Textarea::make('rejection_comment')
                            ->label(__('app.rejection_comment'))
                            ->required()
                            ->rows(4)
                            ->placeholder(__('app.rejection_comment_placeholder'))
                            ->helperText(__('app.rejection_comment_helper')),
                    ])
                    ->modalHeading(__('app.reject_application'))
                    ->modalDescription(__('app.provide_rejection_reason'))
                    ->action(function ($record, array $data) {
                        $record->update([
                            'status' => 'rejected',
                            'feedback_from_hr' => $data['rejection_comment'],
                        ]);

                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title(__('app.application_rejected'))
                            ->body(__('app.application_rejected_notified'))
                            ->send();
                    }),
                Tables\Actions\EditAction::make()
                    ->visible(fn () => auth()->user()->isAdmin()),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn () => auth()->user()->isAdmin()),
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
            'index' => Pages\ListApplications::route('/'),
            'edit' => Pages\EditApplication::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        // Show Applications to Admin, HR, and Candidates
        return auth()->user()->isAdmin() || auth()->user()->isHR() || auth()->user()->isCandidate();
    }

    public static function canCreate(): bool
    {
        // No one can create applications directly - they must apply through jobs
        return false;
    }

    public static function canViewAny(): bool
    {
        // Allow Admin, HR, and Candidates to view applications
        $user = auth()->user();
        if (!$user) {
            return false;
        }
        return $user->isAdmin() || $user->isHR() || $user->isCandidate();
    }

    public static function canView($record): bool
    {
        // Allow Admin, HR, and Candidates to view individual applications
        $user = auth()->user();
        if (!$user) {
            return false;
        }
        
        if ($user->isAdmin()) {
            return true;
        }
        
        if ($user->isHR() && $user->hr) {
            // HR can view applications for their jobs
            return $record->job && $record->job->hr_id === $user->hr->id;
        }
        
        if ($user->isCandidate() && $user->candidate) {
            // Candidates can view their own applications
            return $record->candidate_id === $user->candidate->id;
        }
        
        return false;
    }

    public static function canEdit($record): bool
    {
        // Only Admin can edit applications directly
        // HR can only use Accept/Reject/Hire actions
        $user = auth()->user();
        if (!$user) {
            return false;
        }
        
        return $user->isAdmin();
    }
}

