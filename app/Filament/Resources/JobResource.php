<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JobResource\Pages;
use App\Models\Job;
use App\Models\Application;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class JobResource extends Resource
{
    protected static ?string $model = Job::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationGroup = 'Jobs';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('hr_id')
                    ->relationship('hr', 'company_name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->visible(fn () => auth()->user()->isAdmin()),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\RichEditor::make('description')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('location')
                    ->maxLength(255),
                Forms\Components\Select::make('job_type')
                    ->options([
                        'full-time' => 'Full Time',
                        'part-time' => 'Part Time',
                        'contract' => 'Contract',
                        'internship' => 'Internship',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('salary_range')
                    ->maxLength(255)
                    ->placeholder('e.g., $50,000 - $70,000'),
                Forms\Components\Select::make('experience_level')
                    ->options([
                        'entry' => 'Entry Level',
                        'mid' => 'Mid Level',
                        'senior' => 'Senior Level',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('category')
                    ->maxLength(255),
                Forms\Components\DatePicker::make('application_deadline')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        'active' => 'Active',
                        'closed' => 'Closed',
                    ])
                    ->required()
                    ->default('active'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('hr.company_name')
                    ->searchable()
                    ->sortable()
                    ->visible(fn () => auth()->user()->isAdmin() || auth()->user()->isCandidate()),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('location')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('job_type')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('salary_range')
                    ->searchable(),
                Tables\Columns\TextColumn::make('experience_level')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'entry' => 'success',
                        'mid' => 'warning',
                        'senior' => 'danger',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('application_deadline')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'closed' => 'danger',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('applications_count')
                    ->counts('applications')
                    ->label('Applications')
                    ->sortable()
                    ->visible(fn () => auth()->user()->isAdmin() || auth()->user()->isHR()),
                Tables\Columns\IconColumn::make('has_applied')
                    ->label('Applied')
                    ->boolean()
                    ->getStateUsing(function ($record) {
                        if (!auth()->user()->isCandidate() || !auth()->user()->candidate) {
                            return false;
                        }
                        return \App\Models\Application::where('job_id', $record->id)
                            ->where('candidate_id', auth()->user()->candidate->id)
                            ->exists();
                    })
                    ->visible(fn () => auth()->user()->isCandidate()),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'closed' => 'Closed',
                    ]),
                Tables\Filters\SelectFilter::make('job_type')
                    ->options([
                        'full-time' => 'Full Time',
                        'part-time' => 'Part Time',
                        'contract' => 'Contract',
                        'internship' => 'Internship',
                    ]),
                Tables\Filters\SelectFilter::make('experience_level')
                    ->options([
                        'entry' => 'Entry Level',
                        'mid' => 'Mid Level',
                        'senior' => 'Senior Level',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('apply')
                    ->label('Apply')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->visible(function ($record) {
                        if (!auth()->user()->isCandidate()) {
                            return false;
                        }
                        
                        // Check if already applied
                        if (auth()->user()->candidate) {
                            $hasApplied = \App\Models\Application::where('job_id', $record->id)
                                ->where('candidate_id', auth()->user()->candidate->id)
                                ->exists();
                            return $record->status === 'active' && !$hasApplied;
                        }
                        
                        return $record->status === 'active';
                    })
                    ->modalHeading(fn ($record) => 'Apply for: ' . $record->title)
                    ->modalDescription('Review job details and upload your resume')
                    ->modalContent(function ($record) {
                        $html = '<div class="space-y-4 mb-6 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">';
                        $html .= '<div class="grid grid-cols-2 gap-4 mb-4">';
                        $html .= '<div><p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Company</p><p class="text-sm text-gray-900 dark:text-white">' . htmlspecialchars($record->hr->company_name ?? 'N/A') . '</p></div>';
                        $html .= '<div><p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Location</p><p class="text-sm text-gray-900 dark:text-white">' . htmlspecialchars($record->location ?? 'N/A') . '</p></div>';
                        $html .= '<div><p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Job Type</p><p class="text-sm text-gray-900 dark:text-white">' . ucfirst(str_replace('-', ' ', $record->job_type)) . '</p></div>';
                        $html .= '<div><p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Experience Level</p><p class="text-sm text-gray-900 dark:text-white">' . ucfirst($record->experience_level) . '</p></div>';
                        $html .= '<div><p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Salary Range</p><p class="text-sm text-gray-900 dark:text-white">' . htmlspecialchars($record->salary_range ?? 'Not specified') . '</p></div>';
                        $html .= '<div><p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Application Deadline</p><p class="text-sm text-gray-900 dark:text-white">' . $record->application_deadline->format('M d, Y') . '</p></div>';
                        $html .= '</div>';
                        $html .= '<div class="border-t border-gray-200 dark:border-gray-700 pt-4">';
                        $html .= '<p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Job Description</p>';
                        $html .= '<div class="text-sm text-gray-900 dark:text-white prose prose-sm max-w-none dark:prose-invert">' . $record->description . '</div>';
                        $html .= '</div>';
                        $html .= '</div>';
                        return new HtmlString($html);
                    })
                    ->form([
                        Forms\Components\Toggle::make('use_existing_resume')
                            ->label('Use my profile resume')
                            ->default(fn () => auth()->user()->candidate && auth()->user()->candidate->resume_path ? true : false)
                            ->live()
                            ->disabled(fn () => !auth()->user()->candidate || !auth()->user()->candidate->resume_path)
                            ->helperText(function () {
                                if (auth()->user()->candidate && auth()->user()->candidate->resume_path) {
                                    return 'Use the resume from your candidate profile';
                                }
                                return 'No resume in your profile. Please upload one below.';
                            }),
                        Forms\Components\FileUpload::make('resume')
                            ->label('Or Upload New Resume')
                            ->directory('application-resumes')
                            ->visibility('public')
                            ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                            ->required(fn ($get) => !$get('use_existing_resume'))
                            ->visible(fn ($get) => !$get('use_existing_resume'))
                            ->helperText('Upload your resume (PDF, DOC, or DOCX). Max size: 10MB'),
                    ])
                    ->action(function ($record, array $data) {
                        $user = auth()->user();
                        
                        if (!$user->candidate) {
                            throw new \Exception('Candidate profile not found. Please complete your profile first.');
                        }

                        // Check if already applied
                        $existingApplication = Application::where('job_id', $record->id)
                            ->where('candidate_id', $user->candidate->id)
                            ->first();

                        if ($existingApplication) {
                            throw new \Exception('You have already applied for this job.');
                        }

                        // Check if job is still active
                        if ($record->status !== 'active') {
                            throw new \Exception('This job is no longer accepting applications.');
                        }

                        // Check deadline
                        if ($record->application_deadline < now()->toDateString()) {
                            throw new \Exception('The application deadline for this job has passed.');
                        }

                        // Determine resume path
                        $resumePath = null;
                        if (!empty($data['use_existing_resume']) && $user->candidate->resume_path) {
                            $resumePath = $user->candidate->resume_path;
                        } elseif (!empty($data['resume'])) {
                            $resumePath = $data['resume'];
                        } else {
                            throw new \Exception('Please either use your profile resume or upload a new one.');
                        }

                        // Create application
                        Application::create([
                            'job_id' => $record->id,
                            'candidate_id' => $user->candidate->id,
                            'resume_path' => $resumePath,
                            'status' => 'pending',
                            'applied_at' => now(),
                        ]);

                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title('Application Submitted Successfully!')
                            ->body('Your application for "' . $record->title . '" has been submitted.')
                            ->send();
                    }),
                Tables\Actions\EditAction::make()
                    ->visible(fn () => auth()->user()->isAdmin() || auth()->user()->isHR()),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn () => auth()->user()->isAdmin() || auth()->user()->isHR()),
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
            'index' => Pages\ListJobs::route('/'),
            'create' => Pages\CreateJob::route('/create'),
            'edit' => Pages\EditJob::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        // Show Jobs resource to Admin, HR, and Candidates
        return auth()->user()->isAdmin() || auth()->user()->isHR() || auth()->user()->isCandidate();
    }

    public static function canCreate(): bool
    {
        // Only Admin and HR can create jobs
        return auth()->user()->isAdmin() || auth()->user()->isHR();
    }
}

