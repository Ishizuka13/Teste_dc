<?php

namespace App\Http\Controllers\Auth\Mails;

use App\Http\Controllers\Controller;
use App\Mail\ReserveEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AuthMailController extends Controller
{
    public function sendReserveMail(
        $user_email,
        $reservationDetails,
    ) {
        $reserveEmail = new ReserveEmail($reservationDetails);

        Mail::to($user_email)->queue($reserveEmail);
    }
}
