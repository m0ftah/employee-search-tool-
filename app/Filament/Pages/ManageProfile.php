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
        $this->form->fill(auth()->user()->attributesToArray());
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

                        // البريد الإلكتروني (للقراءة فقط)
                        TextInput::make('email')
                            ->label(__('common.email'))
                            ->disabled()
                            ->email(),

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
                            ->rule(Password::default())
                            ->nullable()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state)),
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

            auth()->user()->update($state);

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
                ->body(__('common.update_error_body'))
                ->send();
        }
    }
}