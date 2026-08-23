<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;

class ContactMessageService
{
    /**
     * @param  array{name: string, email: string, phone?: string|null, subject: string, message: string}  $message
     */
    public function send(array $message): void
    {
        $recipient = config('services.contact.recipient', 'ozivajka@inbox.lv');

        Mail::raw($this->formatMessage($message), function ($mail) use ($message, $recipient) {
            $mail
                ->to($recipient)
                ->replyTo($message['email'], $message['name'])
                ->subject('Wish Gift contact form: '.$message['subject']);
        });
    }

    /**
     * @param  array{name: string, email: string, phone?: string|null, subject: string, message: string}  $message
     */
    private function formatMessage(array $message): string
    {
        return implode("\n", [
            'New message from Wish Gift contact form',
            '',
            'Name: '.$message['name'],
            'Email: '.$message['email'],
            'Phone: '.($message['phone'] ?: '-'),
            'Subject: '.$message['subject'],
            '',
            'Message:',
            $message['message'],
        ]);
    }
}
