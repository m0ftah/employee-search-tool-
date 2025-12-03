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

    protected static ?string $navigationGroup = 'Jobs';

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
                        'pending' => 'Pending',
                        'reviewed' => 'Reviewed',
                        'shortlisted' => 'Shortlisted',
                        'rejected' => 'Rejected',
                        'hired' => 'Hired',
                    ])
                    ->required()
                    ->default('pending'),
                Forms\Components\Textarea::make('feedback_from_hr')
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('feedback_from_candidate')
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
                    ->label('Company')
                    ->searchable()
                    ->sortable()
                    ->visible(fn () => auth()->user()->isAdmin() || auth()->user()->isCandidate()),
                Tables\Columns\TextColumn::make('candidate.user.name')
                    ->label('Candidate')
                    ->searchable()
                    ->sortable()
                    ->visible(fn () => auth()->user()->isAdmin() || auth()->user()->isHR()),
                Tables\Columns\TextColumn::make('candidate.user.email')
                    ->label('Email')
                    ->searchable()
                    ->visible(fn () => auth()->user()->isAdmin() || auth()->user()->isHR()),
                Tables\Columns\TextColumn::make('cv')
                    ->label('CV/Resume')
                    ->getStateUsing(function ($record) {
                        // First check if application has a resume
                        if ($record->resume_path) {
                            return 'View CV';
                        }
                        // Fall back to candidate's profile resume
                        if ($record->candidate && $record->candidate->resume_path) {
                            return 'View CV';
                        }
                        return 'No CV';
                    })
                    ->icon(function ($state) {
                        return $state === 'View CV' ? 'heroicon-o-document-text' : 'heroicon-o-x-circle';
                    })
                    ->color(function ($state) {
                        return $state === 'View CV' ? 'success' : 'gray';
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
                    ->label('HR Feedback')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->feedback_from_hr)
                    ->visible(fn () => auth()->user()->isAdmin() || auth()->user()->isHR())
                    ->wrap(),
                Tables\Columns\TextColumn::make('applied_at')
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
                        'pending' => 'Pending',
                        'reviewed' => 'Reviewed',
                        'shortlisted' => 'Shortlisted',
                        'rejected' => 'Rejected',
                        'hired' => 'Hired',
                    ]),
                Tables\Filters\SelectFilter::make('job_id')
                    ->relationship('job', 'title')
                    ->label('Job'),
            ])
            ->actions([
                Tables\Actions\Action::make('accept')
                    ->label('Accept')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => auth()->user()->isHR() && in_array($record->status, ['pending', 'reviewed']))
                    ->requiresConfirmation()
                    ->modalHeading('Accept Application')
                    ->modalDescription('Are you sure you want to accept this application? It will be moved to shortlisted.')
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'shortlisted',
                        ]);

                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title('Application Accepted')
                            ->body('The application has been accepted and moved to shortlisted.')
                            ->send();
                    }),
                Tables\Actions\Action::make('hire')
                    ->label('Hire')
                    ->icon('heroicon-o-star')
                    ->color('success')
                    ->visible(fn ($record) => auth()->user()->isHR() && $record->status === 'shortlisted')
                    ->requiresConfirmation()
                    ->modalHeading('Hire Candidate')
                    ->modalDescription('Are you sure you want to hire this candidate?')
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'hired',
                        ]);

                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title('Candidate Hired')
                            ->body('The candidate has been marked as hired.')
                            ->send();
                    }),
                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record) => auth()->user()->isHR() && in_array($record->status, ['pending', 'reviewed', 'shortlisted']))
                    ->form([
                        Forms\Components\Textarea::make('rejection_comment')
                            ->label('Rejection Comment')
                            ->required()
                            ->rows(4)
                            ->placeholder('Please provide a reason for rejection...')
                            ->helperText('This comment will be visible to the candidate.'),
                    ])
                    ->modalHeading('Reject Application')
                    ->modalDescription('Please provide a reason for rejecting this application.')
                    ->action(function ($record, array $data) {
                        $record->update([
                            'status' => 'rejected',
                            'feedback_from_hr' => $data['rejection_comment'],
                        ]);

                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title('Application Rejected')
                            ->body('The application has been rejected and the candidate has been notified.')
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

