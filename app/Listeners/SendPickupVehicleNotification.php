<?php

namespace App\Listeners;

use App\Events\PickupVehicle;
use App\Mail\PickupVehicleMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendPickupVehicleNotification implements ShouldQueue {
    /**
     * Create the event listener.
     */
    public function __construct() {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PickupVehicle $event): void {
        Mail::to(config('mail.to_default'))->send(new PickupVehicleMail($event->trip));
    }
}
