<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

// 会員登録のあとに送る「ウェルカムメール」
class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    // 送るときに、ユーザーの名前を受け取る(public なので、メールの本文でも $userName として使える)
    public function __construct(public string $userName) {}

    // メールの「件名」を決める
    public function envelope(): Envelope
    {
        return new Envelope(subject: 'ご登録ありがとうございます');
    }

    // メールの「本文」に使う画面(resources/views/emails/welcome.blade.php)を決める
    public function content(): Content
    {
        return new Content(view: 'emails.welcome');
    }
}