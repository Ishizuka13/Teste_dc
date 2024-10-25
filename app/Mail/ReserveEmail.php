<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReserveEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $reservationDetails;

    public function __construct(
        $reservationDetails,
    ) {
        $this->reservationDetails = $reservationDetails;
    }

    public function envelope()
    {
        return new \Illuminate\Mail\Mailables\Envelope(
            subject: 'Boas Vindas Email',
        );
    }

    public function build()
    {
        return $this->subject('Boas Vindas Email')
            ->view('email.reserveEmail')->with('reservationDetails', $this->reservationDetails)
            ->subject('Confirmação da Reserva');
    }

    public function attachments(): array
    {
        return [];
    }
}
