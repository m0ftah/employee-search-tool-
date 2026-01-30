<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ManageProfile extends Page
{
    // أيقونة الصفحة في القائمة الجانبية
    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    // الربط مع ملف الـ Blade المخصص
    protected static string $view = 'filament.pages.manage-profile';

    // ترجمة عنوان القائمة الجانبية
    public static function getNavigationLabel(): string
    {
        return __('common.manage_profile');
    }

    // ترجمة عنوان الصفحة الرئيسي
    public function getTitle(): string
    {
        return __('common.manage_profile');
    }

    public ?array $data = [];

    public function mount(): void
    {
        // Pre-condition: تحميل بيانات المستخدم المسجل حالياً
        $user = auth()->user();
        $userData = $user->attributesToArray();
        
        if ($user->isCandidate() && $user->candidate) {
            $userData = array_merge($userData, $user->candidate->attributesToArray());
        }
        
        $this->form->fill($userData);
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Section::make(__('common.profile_info'))
                    ->description(__('common.profile_subtitle'))
                    ->schema([
                        // Main Scenario: تحديث الاسم
                        TextInput::make('name')
                            ->label(__('common.name'))
                            ->required()
                            ->maxLength(255),

                        // البريد الإلكتروني
                        TextInput::make('email')
                            ->label(__('common.email'))
                            ->required()
                            ->email()
                            ->maxLength(255)
                            ->unique('users', 'email', ignorable: auth()->user()),

                        // حقل كلمة المرور الحالية
                        TextInput::make('current_password')
                            ->label(__('common.current_password'))
                            ->password()
                            ->requiredWith('password')
                            ->currentPassword() // يتحقق من صحة كلمة المرور الحالية
                            ->dehydrated(false),

                        // كلمة المرور الجديدة
                        TextInput::make('password')
                            ->label(__('common.password'))
                            ->password()
                            ->helperText(__('common.password_helper'))
                            ->rule(\Illuminate\Validation\Rules\Password::default())
                            ->nullable()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state)),
                    ])->columns(2),

                Section::make(__('app.candidate_profile'))
                    ->visible(fn () => auth()->user()->isCandidate())
                    ->schema([
                        TextInput::make('phone')
                            ->label(__('app.phone_number'))
                            ->tel()
                            ->maxLength(255),
                        TextInput::make('location')
                            ->label(__('app.location'))
                            ->maxLength(255),
                        \Filament\Forms\Components\Select::make('education_level')
                            ->label(__('app.education_level'))
                            ->options([
                                'high_school' => __('app.high_school'),
                                'diploma' => __('app.diploma'),
                                'bachelor' => __('app.bachelor'),
                                'master' => __('app.master'),
                                'phd' => __('app.phd'),
                            ]),
                        TextInput::make('years_of_experience')
                            ->label(__('app.years_of_experience'))
                            ->numeric(),
                        \Filament\Forms\Components\TagsInput::make('skills')
                            ->label(__('app.skills'))
                            ->placeholder(__('app.skills_placeholder')),
                        \Filament\Forms\Components\Textarea::make('certifications')
                            ->label(__('app.certifications'))
                            ->placeholder(__('app.list_certifications'))
                            ->rows(3)
                            ->columnSpanFull(),
                        \Filament\Forms\Components\Textarea::make('bio')
                            ->label(__('app.bio'))
                            ->placeholder(__('app.tell_us_about_yourself_placeholder'))
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    // تعريف أزرار الأكشن (زر الحفظ)
    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('common.save_changes'))
                ->submit('save')
                ->color('primary'),
        ];
    }

    public function save(): void
    {
        try {
            // تنفيذ الـ Validation (E1 Scenario)
            $state = $this->form->getState();

            $user = auth()->user();
            
            // Separate user data from candidate data
            $userData = [
                'name' => $state['name'],
                'email' => $state['email'],
            ];
            
            if (isset($state['password'])) {
                $userData['password'] = $state['password'];
            }
            
            $user->update($userData);

            if ($user->isCandidate()) {
                $candidateData = [
                    'phone' => $state['phone'] ?? null,
                    'location' => $state['location'] ?? null,
                    'education_level' => $state['education_level'] ?? null,
                    'years_of_experience' => $state['years_of_experience'] ?? null,
                    'skills' => $state['skills'] ?? [],
                    'certifications' => $state['certifications'] ?? null,
                    'bio' => $state['bio'] ?? null,
                ];
                
                if ($user->candidate) {
                    $user->candidate->update($candidateData);
                } else {
                    $user->candidate()->create($candidateData);
                }
            }

            // Post Condition: إرسال تنبيه بالنجاح
            Notification::make()
                ->success()
                ->title(__('common.update_success_title'))
                ->body(__('common.update_success_body'))
                ->send();

        } catch (\Exception $e) {
            // Exceptional Scenario (E1): فشل التحديث
            Notification::make()
                ->danger()
                ->title(__('common.update_error_title'))
                ->body(__('common.update_error_body') . ': ' . $e->getMessage())
                ->send();
        }
    }
}