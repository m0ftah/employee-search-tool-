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
                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                    ->disabled(fn () => auth()->user()->isCandidate()),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => __('app.pending'),
                        'reviewed' => __('app.reviewed'),
                        'shortlisted' => __('app.shortlisted'),
                        'rejected' => __('app.rejected'),
                        'hired' => __('app.hired'),
                    ])
                    ->required()
                    ->default('pending')
                    ->disabled(fn () => auth()->user()->isCandidate()),
                Forms\Components\Textarea::make('feedback_from_hr')
                    ->label(__('app.hr_feedback'))
                    ->rows(3)
                    ->columnSpanFull()
                    ->disabled(fn () => auth()->user()->isCandidate())
                    ->visible(fn () => auth()->user()->isAdmin() || auth()->user()->isHR()),
                Forms\Components\Textarea::make('feedback_from_candidate')
                    ->label(__('app.candidate_feedback'))
                    ->rows(3)
                    ->columnSpanFull()
                    ->placeholder(__('app.candidate_feedback_placeholder'))
                    ->helperText(__('app.candidate_feedback_helper'))
                    ->visible(fn () => auth()->user()->isAdmin() || auth()->user()->isCandidate())
                    ->disabled(fn () => auth()->user()->isAdmin() || auth()->user()->isHR()),
                Forms\Components\DateTimePicker::make('applied_at')
                    ->default(now())
                    ->required()
                    ->disabled(fn () => auth()->user()->isCandidate()),
                Forms\Components\TextInput::make('score')
                    ->label(__('app.cv_score'))
                    ->numeric()
                    ->disabled()
                    ->visible(fn () => auth()->user()->isAdmin() || auth()->user()->isHR()),
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
                Tables\Columns\TextColumn::make('score')
                    ->label(__('app.cv_score'))
                    ->getStateUsing(fn ($record) => $record->score ?? $record->candidate?->score)
                    ->numeric(
                        decimalPlaces: 0,
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
                Tables\Columns\TextColumn::make('feedback_from_candidate')
                    ->label(__('app.candidate_feedback'))
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->feedback_from_candidate)
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
                    ->label(__('common.status'))
                    ->options([
                        'pending' => __('app.pending'),
                        'reviewed' => __('app.reviewed'),
                        'shortlisted' => __('app.shortlisted'),
                        'rejected' => __('app.rejected'),
                        'hired' => __('app.hired'),
                    ])
                    ->multiple(),
                Tables\Filters\SelectFilter::make('job_id')
                    ->label(__('app.job_title'))
                    ->relationship('job', 'title')
                    ->searchable()
                    ->preload()
                    ->multiple(),
                Tables\Filters\SelectFilter::make('candidate_id')
                    ->label(__('app.candidate'))
                    ->relationship('candidate.user', 'name')
                    ->searchable()
                    ->preload()
                    ->visible(fn () => auth()->user()->isAdmin() || auth()->user()->isHR())
                    ->multiple(),
                Tables\Filters\SelectFilter::make('job.hr.company_name')
                    ->label(__('app.company_name'))
                    ->relationship('job.hr', 'company_name')
                    ->searchable()
                    ->preload()
                    ->visible(fn () => auth()->user()->isAdmin() || auth()->user()->isCandidate())
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
                            ->placeholder('10')
                            ->numeric(),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['score_from'],
                                fn ($query, $score) => $query->whereHas('candidate', function ($q) use ($score) {
                                    $q->where('score', '>=', $score);
                                })
                            )
                            ->when(
                                $data['score_to'],
                                fn ($query, $score) => $query->whereHas('candidate', function ($q) use ($score) {
                                    $q->where('score', '<=', $score);
                                })
                            );
                    })
                    ->visible(fn () => auth()->user()->isAdmin() || auth()->user()->isHR()),
                Tables\Filters\Filter::make('applied_at')
                    ->label(__('app.applied_at'))
                    ->form([
                        Forms\Components\DatePicker::make('applied_from')
                            ->label(__('app.from_date')),
                        Forms\Components\DatePicker::make('applied_to')
                            ->label(__('app.to_date')),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['applied_from'],
                                fn ($query, $date) => $query->whereDate('applied_at', '>=', $date)
                            )
                            ->when(
                                $data['applied_to'],
                                fn ($query, $date) => $query->whereDate('applied_at', '<=', $date)
                            );
                    }),
                Tables\Filters\Filter::make('has_resume')
                    ->label(__('app.has_resume'))
                    ->query(fn ($query) => $query->where(function ($q) {
                        $q->whereNotNull('resume_path')
                            ->orWhereHas('candidate', fn ($q) => $q->whereNotNull('resume_path'));
                    }))
                    ->visible(fn () => auth()->user()->isAdmin() || auth()->user()->isHR())
                    ->toggle(),
                Tables\Filters\Filter::make('has_feedback')
                    ->label(__('app.has_hr_feedback'))
                    ->query(fn ($query) => $query->whereNotNull('feedback_from_hr')->where('feedback_from_hr', '!=', ''))
                    ->visible(fn () => auth()->user()->isAdmin() || auth()->user()->isHR())
                    ->toggle(),
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
                Tables\Actions\Action::make('accept')
                    ->label(__('app.accept'))
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => auth()->user()->isHR() && in_array($record->status, ['pending', 'reviewed']))
                    ->form([
                        Forms\Components\Textarea::make('hr_feedback')
                            ->label(__('app.acceptance_feedback'))
                            ->rows(4)
                            ->placeholder(__('app.acceptance_feedback_placeholder'))
                            ->helperText(__('app.acceptance_feedback_helper'))
                            ->required(),
                    ])
                    ->modalHeading(__('app.accept_application'))
                    ->modalDescription(__('app.accept_application_description'))
                    ->action(function ($record, array $data) {
                        $record->update([
                            'status' => 'shortlisted',
                            'feedback_from_hr' => $data['hr_feedback'],
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
                    ->form([
                        Forms\Components\Textarea::make('hr_feedback')
                            ->label(__('app.hiring_feedback'))
                            ->rows(4)
                            ->placeholder(__('app.hiring_feedback_placeholder'))
                            ->helperText(__('app.hiring_feedback_helper'))
                            ->required(),
                    ])
                    ->modalHeading(__('app.hire_candidate'))
                    ->modalDescription(__('app.hire_candidate_description'))
                    ->action(function ($record, array $data) {
                        $record->update([
                            'status' => 'hired',
                            'feedback_from_hr' => $data['hr_feedback'],
                        ]);

                        // Send email notification to candidate
                        if ($record->candidate && $record->candidate->user) {
                            $record->candidate->user->notify(
                                new \App\Notifications\ApplicationAcceptedNotification($record)
                            );
                        }

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

                        // Send email notification to candidate
                        if ($record->candidate && $record->candidate->user) {
                            $record->candidate->user->notify(
                                new \App\Notifications\ApplicationRejectedNotification($record)
                            );
                        }

                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title(__('app.application_rejected'))
                            ->body(__('app.application_rejected_notified'))
                            ->send();
                    }),
                Tables\Actions\Action::make('provide_feedback')
                    ->label(__('app.provide_feedback'))
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('info')
                    ->visible(fn ($record) => auth()->user()->isCandidate() && $record->candidate_id === auth()->user()->candidate?->id)
                    ->form([
                        Forms\Components\Textarea::make('feedback_from_candidate')
                            ->label(__('app.candidate_feedback'))
                            ->placeholder(__('app.candidate_feedback_placeholder'))
                            ->helperText(__('app.candidate_feedback_helper'))
                            ->rows(5)
                            ->required()
                            ->maxLength(1000),
                    ])
                    ->modalHeading(__('app.provide_feedback_to_hr'))
                    ->modalDescription(__('app.provide_feedback_description'))
                    ->action(function ($record, array $data) {
                        $record->update([
                            'feedback_from_candidate' => $data['feedback_from_candidate'],
                        ]);

                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title(__('app.feedback_submitted'))
                            ->body(__('app.feedback_submitted_success'))
                            ->send();
                    }),
                Tables\Actions\EditAction::make()
                    ->visible(fn () => auth()->user()->isAdmin() || auth()->user()->isCandidate()),
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

