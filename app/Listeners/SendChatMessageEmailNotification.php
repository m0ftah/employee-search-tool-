<?php

namespace App\Listeners;

use App\Notifications\NewChatMessageNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Namu\WireChat\Events\NotifyParticipant;

class SendChatMessageEmailNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NotifyParticipant $event): void
    {
        try {
            $message = $event->message;
            $participant = $event->participant;

            // Load relationships if needed
            $message->loadMissing(['sendable', 'conversation']);
            
            // Get the sender
            $sender = $message->sendable;
            if (!$sender) {
                \Log::warning('Wirechat email notification: No sender found for message', [
                    'message_id' => $message->id
                ]);
                return;
            }

            // Get the recipient from the participant
            $recipient = null;
            if ($participant instanceof \Namu\WireChat\Models\Participant) {
                // Load the participantable relationship if not loaded
                if (!$participant->relationLoaded('participantable')) {
                    $participant->load('participantable');
                }
                $recipient = $participant->participantable;
            } elseif ($participant instanceof \Illuminate\Database\Eloquent\Model) {
                $recipient = $participant;
            }

            if (!$recipient) {
                \Log::warning('Wirechat email notification: No recipient found', [
                    'message_id' => $message->id,
                    'participant_type' => get_class($participant)
                ]);
                return;
            }

            // Skip if recipient is the sender
            if ($recipient->id === $sender->id && get_class($recipient) === get_class($sender)) {
                \Log::debug('Wirechat email notification: Skipping sender', [
                    'message_id' => $message->id,
                    'sender_id' => $sender->id
                ]);
                return;
            }

            // Only send if recipient is a User model and has email
            if ($recipient instanceof \App\Models\User && $recipient->email) {
                \Log::info('Wirechat email notification: Sending email', [
                    'message_id' => $message->id,
                    'recipient_id' => $recipient->id,
                    'recipient_email' => $recipient->email,
                    'sender_id' => $sender->id
                ]);
                $recipient->notify(new NewChatMessageNotification($message, $message->conversation, $sender));
            } else {
                \Log::warning('Wirechat email notification: Recipient is not a User or has no email', [
                    'message_id' => $message->id,
                    'recipient_type' => get_class($recipient),
                    'recipient_id' => $recipient->id ?? null,
                    'has_email' => isset($recipient->email) && !empty($recipient->email)
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Wirechat email notification error: ' . $e->getMessage(), [
                'exception' => $e,
                'message_id' => $event->message->id ?? null
            ]);
        }
    }
}
