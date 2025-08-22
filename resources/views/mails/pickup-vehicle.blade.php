{{ $trip->vehicle->licence_plate }} felvéve {{ $trip->user->name }}
{{ $trip->pickup_at->setTimezone('Europe/Budapest')->format('Y-m-d H:i') }}
