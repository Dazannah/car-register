<?php

namespace App\Listeners;

use App\Events\ReturnVehicle;
use App\Mail\ReturnVehicleMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendReturnVehicleNotification implements ShouldQueue {
    /**
     * Create the event listener.
     */
    public function __construct() {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ReturnVehicle $event): void {

        Mail::to(config('mail.to_default'))->send(new ReturnVehicleMail($event->trip));
    }
}
