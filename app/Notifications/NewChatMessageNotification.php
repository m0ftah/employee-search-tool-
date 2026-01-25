<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;
use Namu\WireChat\Models\Conversation;
use Namu\WireChat\Models\Message;

class NewChatMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Message $message,
        public Conversation $conversation,
        public $sender
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
        // Load relationships if needed
        $this->message->loadMissing(['sendable', 'conversation']);
        $this->conversation->loadMissing('participants.participantable');

        $senderName = $this->sender->name ?? __('app.user');
        $messagePreview = $this->message->body ? Str::limit(strip_tags($this->message->body), 100) : __('app.no_message_content');
        
        // Get the chat URL - Wirechat uses /chats prefix by default
        $prefix = config('wirechat.routes.prefix', 'chats');
        $chatUrl = url('/admin/' . $prefix);

        return (new MailMessage)
            ->subject(__('app.new_chat_message_subject', ['sender' => $senderName]))
            ->greeting(__('app.hello') . ' ' . $notifiable->name . '!')
            ->line(__('app.new_chat_message_intro', ['sender' => $senderName]))
            ->line('**' . __('app.message') . ':** ' . $messagePreview)
            ->action(__('app.view_chat'), $chatUrl)
            ->line(__('app.new_chat_message_closing'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message_id' => $this->message->id,
            'conversation_id' => $this->conversation->id,
            'sender_id' => $this->sender->id,
            'sender_name' => $this->sender->name ?? null,
        ];
    }
}
