<?php

namespace App\Mail;

use App\Models\Lietotajs;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerifyEmailMailable extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Lietotāja modelis
     * 
     * @var \App\Models\Lietotajs
     */
    public Lietotajs $lietotajs;

    /**
     * Verifikācijas kods
     * 
     * @var string
     */
    public string $verificationCode;

    /**
     * Uztaisīt jaunu Mailable klasi
     * 
     * @param \App\Models\Lietotajs $lietotajs
     * @param string $verificationCode
     * @return void
     */
    public function __construct(Lietotajs $lietotajs, string $verificationCode)
    {
        $this->lietotajs = $lietotajs;
        $this->verificationCode = $verificationCode;
    }

    /**
     * Saņemt pastu aplokni
     * 
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(env('MAIL_FROM_ADDRESS', 'noreply@rcbuzskaite.lv'), env('MAIL_FROM_NAME', 'RCB Uzskaite')),
            subject: 'E-pasta verifikācija - RCB Uzskaite',
        );
    }

    /**
     * Saņemt e-pasta saturu
     * 
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.verify-email',
            with: [
                'lietotajs' => $this->lietotajs,
                'verificationCode' => $this->verificationCode,
                'appName' => 'RCB Uzskaite',
            ],
        );
    }

    /**
     * Saņemt e-pasta pielikumus
     * 
     * @return array
     */
    public function attachments(): array
    {
        return [];
    }
}
