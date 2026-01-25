<?php

namespace App\Notifications;

use App\Models\Job;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewJobPostedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Job $job
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
        $this->job->loadMissing('hr');
        $jobUrl = url('/admin/jobs/' . $this->job->id);
        
        return (new MailMessage)
            ->subject(__('app.new_job_posted_subject', ['title' => $this->job->title]))
            ->greeting(__('app.hello') . ' ' . $notifiable->name . '!')
            ->line(__('app.new_job_posted_intro'))
            ->line(__('app.job_title') . ': **' . $this->job->title . '**')
            ->line(__('app.company_name') . ': **' . ($this->job->hr->company_name ?? __('app.not_specified')) . '**')
            ->when($this->job->location, function ($mail) {
                return $mail->line(__('app.location') . ': **' . $this->job->location . '**');
            })
            ->when($this->job->job_type, function ($mail) {
                return $mail->line(__('app.job_type') . ': **' . __('app.' . str_replace('-', '_', $this->job->job_type)) . '**');
            })
            ->when($this->job->salary_range, function ($mail) {
                return $mail->line(__('app.salary_range') . ': **' . $this->job->salary_range . '**');
            })
            ->when($this->job->application_deadline, function ($mail) {
                return $mail->line(__('app.application_deadline') . ': **' . $this->job->application_deadline->format('Y-m-d') . '**');
            })
            ->line(__('app.new_job_posted_description'))
            ->action(__('app.view_job_details'), $jobUrl)
            ->line(__('app.new_job_posted_closing'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'job_id' => $this->job->id,
            'job_title' => $this->job->title,
        ];
    }
}
