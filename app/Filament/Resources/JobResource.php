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
use App\Services\CVTextExtractorService;
use App\Services\CVScoringService;
use Illuminate\Support\Facades\Auth;

class JobResource extends Resource
{
    protected static ?string $model = Job::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationGroup = null;

    public static function getNavigationGroup(): ?string
    {
        return __('app.jobs');
    }

    protected static ?string $navigationLabel = null;

    public static function getNavigationLabel(): string
    {
        return __('app.job_postings');
    }

    public static function getModelLabel(): string
    {
        return __('app.job_title');
    }

    public static function getPluralModelLabel(): string
    {
        return __('app.job_postings');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('hr_id')
                    ->relationship('hr', 'company_name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->visible(fn () => auth()->user()->isAdmin())
                    ->default(function () {
                        // For HR users, set their HR ID as default (even though field is hidden)
                        $user = auth()->user();
                        if ($user->isHR() && $user->hr) {
                            return $user->hr->id;
                        }
                        return null;
                    }),
                Forms\Components\TextInput::make('title')
                    ->label(__('app.job_title'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\RichEditor::make('description')
                    ->label(__('app.job_description'))
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('location')
                    ->label(__('common.location'))
                    ->maxLength(255),
                Forms\Components\Select::make('job_type')
                    ->label(__('app.job_type'))
                    ->options([
                        'full-time' => __('app.full_time'),
                        'part-time' => __('app.part_time'),
                        'contract' => __('app.contract'),
                        'internship' => __('app.internship'),
                    ])
                    ->required(),
                Forms\Components\TextInput::make('salary_range')
                    ->label(__('app.salary_range'))
                    ->maxLength(255)
                    ->placeholder('e.g., $50,000 - $70,000'),
                Forms\Components\Select::make('experience_level')
                    ->label(__('app.experience_level'))
                    ->options([
                        'entry' => __('app.entry_level'),
                        'mid' => __('app.mid_level'),
                        'senior' => __('app.senior_level'),
                    ])
                    ->required(),
                Forms\Components\TextInput::make('category')
                    ->label(__('app.category'))
                    ->maxLength(255),
                Forms\Components\DatePicker::make('application_deadline')
                    ->label(__('app.application_deadline'))
                    ->required(),
                Forms\Components\Select::make('status')
                    ->label(__('common.status'))
                    ->options([
                        'active' => __('app.active'),
                        'closed' => __('app.closed'),
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
                    ->label(__('app.company_name'))
                    ->searchable()
                    ->sortable()
                    ->visible(fn () => auth()->user()->isAdmin() || auth()->user()->isCandidate()),
                Tables\Columns\TextColumn::make('title')
                    ->label(__('app.job_title'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('location')
                    ->label(__('common.location'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('job_type')
                    ->label(__('app.job_type'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'full-time' => __('app.full_time'),
                        'part-time' => __('app.part_time'),
                        'contract' => __('app.contract'),
                        'internship' => __('app.internship'),
                        default => $state,
                    })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('salary_range')
                    ->label(__('app.salary_range'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('experience_level')
                    ->label(__('app.experience_level'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'entry' => __('app.entry_level'),
                        'mid' => __('app.mid_level'),
                        'senior' => __('app.senior_level'),
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'entry' => 'success',
                        'mid' => 'warning',
                        'senior' => 'danger',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category')
                    ->label(__('app.category'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label(__('app.job_description'))
                    ->html()
                    ->limit(100)
                    ->tooltip(fn ($record) => strip_tags($record->description))
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('application_deadline')
                    ->label(__('app.application_deadline'))
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('common.status'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => __('app.active'),
                        'closed' => __('app.closed'),
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'closed' => 'danger',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('applications_count')
                    ->counts('applications')
                    ->label(__('app.applications'))
                    ->sortable()
                    ->visible(fn () => auth()->user()->isAdmin() || auth()->user()->isHR()),
                Tables\Columns\IconColumn::make('has_applied')
                    ->label(__('app.applied'))
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
                    ->label(__('common.status'))
                    ->options([
                        'active' => __('app.active'),
                        'closed' => __('app.closed'),
                    ])
                    ->multiple(),
                Tables\Filters\SelectFilter::make('job_type')
                    ->label(__('app.job_type'))
                    ->options([
                        'full-time' => __('app.full_time'),
                        'part-time' => __('app.part_time'),
                        'contract' => __('app.contract'),
                        'internship' => __('app.internship'),
                    ])
                    ->multiple(),
                Tables\Filters\SelectFilter::make('experience_level')
                    ->label(__('app.experience_level'))
                    ->options([
                        'entry' => __('app.entry_level'),
                        'mid' => __('app.mid_level'),
                        'senior' => __('app.senior_level'),
                    ])
                    ->multiple(),
                Tables\Filters\SelectFilter::make('hr_id')
                    ->label(__('app.company_name'))
                    ->relationship('hr', 'company_name')
                    ->searchable()
                    ->preload()
                    ->visible(fn () => auth()->user()->isAdmin() || auth()->user()->isCandidate())
                    ->multiple(),
                Tables\Filters\Filter::make('title')
                    ->label(__('app.job_title'))
                    ->form([
                        Forms\Components\TextInput::make('title')
                            ->label(__('app.job_title'))
                            ->placeholder(__('app.search_job_title')),
            ])
                    ->query(function ($query, array $data) {
                        return $query->when(
                            $data['title'],
                            fn ($query, $title) => $query->where('title', 'like', "%{$title}%")
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
                Tables\Filters\Filter::make('category')
                    ->label(__('app.category'))
                    ->form([
                        Forms\Components\TextInput::make('category')
                            ->label(__('app.category'))
                            ->placeholder(__('app.search_category')),
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when(
                            $data['category'],
                            fn ($query, $category) => $query->where('category', 'like', "%{$category}%")
                        );
                    }),
                Tables\Filters\Filter::make('salary_range')
                    ->label(__('app.salary_range'))
                    ->form([
                        Forms\Components\TextInput::make('salary_range')
                            ->label(__('app.salary_range'))
                            ->placeholder(__('app.search_salary')),
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when(
                            $data['salary_range'],
                            fn ($query, $salary) => $query->where('salary_range', 'like', "%{$salary}%")
                        );
                    }),
                Tables\Filters\Filter::make('application_deadline')
                    ->label(__('app.application_deadline'))
                    ->form([
                        Forms\Components\DatePicker::make('deadline_from')
                            ->label(__('app.from_date')),
                        Forms\Components\DatePicker::make('deadline_to')
                            ->label(__('app.to_date')),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['deadline_from'],
                                fn ($query, $date) => $query->whereDate('application_deadline', '>=', $date)
                            )
                            ->when(
                                $data['deadline_to'],
                                fn ($query, $date) => $query->whereDate('application_deadline', '<=', $date)
                            );
                    }),
                Tables\Filters\Filter::make('has_applications')
                    ->label(__('app.has_applications'))
                    ->query(fn ($query) => $query->has('applications'))
                    ->visible(fn () => auth()->user()->isAdmin() || auth()->user()->isHR())
                    ->toggle(),
                Tables\Filters\Filter::make('applications_count')
                    ->label(__('app.applications_count'))
                    ->form([
                        Forms\Components\TextInput::make('applications_from')
                            ->label(__('app.min_applications'))
                            ->numeric()
                            ->placeholder('0'),
                        Forms\Components\TextInput::make('applications_to')
                            ->label(__('app.max_applications'))
                            ->numeric()
                            ->placeholder('100'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['applications_from'],
                                fn ($query, $count) => $query->has('applications', '>=', $count)
                            )
                            ->when(
                                $data['applications_to'],
                                fn ($query, $count) => $query->has('applications', '<=', $count)
                            );
                    })
                    ->visible(fn () => auth()->user()->isAdmin() || auth()->user()->isHR()),
                Tables\Filters\Filter::make('deadline_passed')
                    ->label(__('app.deadline_passed'))
                    ->query(fn ($query) => $query->where('application_deadline', '<', now()->toDateString()))
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
                Tables\Actions\Action::make('apply')
                    ->label(__('app.apply'))
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
                    ->modalHeading(fn ($record) => __('app.apply_for') . ': ' . $record->title)
                    ->modalDescription(__('app.review_job_details'))
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
                            ->label(__('app.use_existing_resume'))
                            ->default(fn () => auth()->user()->candidate && auth()->user()->candidate->resume_path ? true : false)
                            ->live()
                            ->disabled(fn () => !auth()->user()->candidate || !auth()->user()->candidate->resume_path)
                            ->helperText(function () {
                                if (auth()->user()->candidate && auth()->user()->candidate->resume_path) {
                                    return __('app.use_profile_resume');
                                }
                                return __('app.no_resume_in_profile');
                            }),
                        Forms\Components\FileUpload::make('resume')
                            ->label(__('app.or_upload_new_resume'))
                            ->directory('application-resumes')
                            ->visibility('public')
                            ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                            ->required(fn ($get) => !$get('use_existing_resume'))
                            ->visible(fn ($get) => !$get('use_existing_resume'))
                            ->helperText(__('app.upload_resume_helper')),
                    ])
                    ->action(function ($record, array $data) {
                        $user = auth()->user();

                        if (!$user->candidate) {
                            \Filament\Notifications\Notification::make()
                                ->danger()
                                ->title(__('app.error'))
                                ->body(__('app.candidate_profile_not_found'))
                                ->send();
                            return;
                        }

                        // Check if already applied
                        $existingApplication = Application::where('job_id', $record->id)
                            ->where('candidate_id', $user->candidate->id)
                            ->first();

                        if ($existingApplication) {
                            \Filament\Notifications\Notification::make()
                                ->warning()
                                ->title(__('app.already_applied'))
                                ->body(__('app.you_have_already_applied_for_this_job'))
                                ->send();
                            return;
                        }

                        // Check if job is still active
                        if ($record->status !== 'active') {
                            \Filament\Notifications\Notification::make()
                                ->warning()
                                ->title(__('app.job_not_accepting_applications'))
                                ->body(__('app.this_job_is_no_longer_accepting_applications'))
                                ->send();
                            return;
                        }

                        // Check deadline
                        if ($record->application_deadline < now()->toDateString()) {
                            $deadlineFormatted = $record->application_deadline->format('M d, Y');
                            \Filament\Notifications\Notification::make()
                                ->warning()
                                ->title(__('app.application_deadline_passed'))
                                ->body(__('app.the_application_deadline_for_this_job_has_passed', ['deadline' => $deadlineFormatted]))
                                ->send();
                            return;
                        }

                        // Determine resume path
                        $resumePath = null;
                        if (!empty($data['use_existing_resume']) && $user->candidate->resume_path) {
                            $resumePath = $user->candidate->resume_path;
                        } elseif (!empty($data['resume'])) {
                            $resumePath = $data['resume'];
                        } else {
                            \Filament\Notifications\Notification::make()
                                ->danger()
                                ->title(__('app.resume_required'))
                                ->body(__('app.please_use_profile_resume_or_upload_new_one'))
                                ->send();
                            return;
                        }

                        // Create application
                        $application = Application::create([
                            'job_id' => $record->id,
                            'candidate_id' => $user->candidate->id,
                            'resume_path' => $resumePath,
                            'status' => 'pending',
                            'applied_at' => now(),
                        ]);

                        // Calculate score if a new resume was uploaded
                        if (empty($data['use_existing_resume']) && !empty($data['resume'])) {
                            try {
                                $textExtractor = new CVTextExtractorService();
                                $cvText = $textExtractor->extractText($resumePath);
                                
                                $scoringService = new CVScoringService();
                                $score = $scoringService->analyzeCV($cvText);
                                
                                if ($score !== null) {
                                    $application->update(['score' => $score]);
                                }
                            } catch (\Exception $e) {
                                \Illuminate\Support\Facades\Log::error('Error scoring application CV: ' . $e->getMessage());
                            }
                        }

                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title(__('app.application_submitted_successfully'))
                            ->body(str_replace(':title', $record->title, __('app.application_submitted_body')))
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

