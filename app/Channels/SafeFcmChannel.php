<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;
use Illuminate\Support\Facades\Log;

class SafeFcmChannel
{
    public function send(object $notifiable, Notification $notification): void
    {
        if (!method_exists($notification, 'toFcm')) {
            return;
        }

        try {
            $data = $notification->toFcm($notifiable);
            
            // If the notification already returns an FcmMessage, use it
            if ($data instanceof FcmMessage) {
                $fcmMessage = $data;
            } else {
                // Convert array to FcmMessage
                $title = $data['title'] ?? 'Notification';
                $body = $data['body'] ?? '';
                $payload = $data['data'] ?? [];

                // Convert all payload values to string (Firebase requirement)
                $stringPayload = [];
                foreach ($payload as $key => $value) {
                    $stringPayload[$key] = (string) $value;
                }

                $fcmMessage = FcmMessage::create()
                    ->setData($stringPayload)
                    ->setNotification(FcmNotification::create()
                        ->setTitle($title)
                        ->setBody($body)
                    );
            }

            // Temporarily replace the notification's toFcm method dynamically if possible,
            // or just use the FcmChannel manually. The laravel-notification-channels/fcm
            // expects the notification to have a toFcm method that returns an FcmMessage.
            // Since we intercepted it, we will create an anonymous class wrapper or mock it.
            
            $wrapper = new class($fcmMessage) extends Notification {
                public function __construct(private FcmMessage $msg) {}
                public function toFcm($notifiable) { return $this->msg; }
            };

            app(FcmChannel::class)->send($notifiable, $wrapper);

        } catch (\Throwable $e) {
            // "bs fte nhi" - Catch everything so the app doesn't crash if Firebase credentials are missing
            Log::warning('FCM Notification Failed (Credentials might be missing): ' . $e->getMessage());
        }
    }
}
