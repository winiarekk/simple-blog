<?php

namespace App\Infrastructure\Laravel\Listeners;

use App\Infrastructure\Laravel\Events\ResetPasswordRequested;
use App\Infrastructure\Laravel\Mail\ResetPasswordMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendResetPasswordEmail implements ShouldQueue
{
    public function __construct()
    {}

    public function handle(ResetPasswordRequested $event): void
    {
        Mail::to($event->email)->send(new ResetPasswordMail($event->token));
    }
}
