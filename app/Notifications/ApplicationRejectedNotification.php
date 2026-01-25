<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationRejectedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Application $application
    ) {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // Ensure relationships are loaded
        $this->application->loadMissing(['job.hr']);
        $job = $this->application->job;
        $applicationUrl = url('/admin/applications/' . $this->application->id);
        
        return (new MailMessage)
            ->subject(__('app.application_rejected_subject', ['title' => $job->title]))
            ->greeting(__('app.hello') . ' ' . $notifiable->name . ',')
            ->line(__('app.application_rejected_intro', ['title' => $job->title]))
            ->line(__('app.company_name') . ': **' . ($job->hr->company_name ?? __('app.not_specified')) . '**')
            ->when($this->application->feedback_from_hr, function ($mail) {
                return $mail->line(__('app.hr_feedback') . ':')
                    ->line($this->application->feedback_from_hr);
            })
            ->line(__('app.application_rejected_encouragement'))
            ->action(__('app.view_application'), $applicationUrl)
            ->line(__('app.application_rejected_closing'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'application_id' => $this->application->id,
            'job_title' => $this->application->job->title,
            'status' => $this->application->status,
        ];
    }
}
