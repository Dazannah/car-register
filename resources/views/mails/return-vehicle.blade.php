{{ $trip->vehicle->licence_plate }} leadva {{ $trip->user->name }}
{{ $trip->return_at->setTimezone('Europe/Budapest')->format('Y-m-d H:i') }}
