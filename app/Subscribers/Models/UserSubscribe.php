<?php

namespace App\Subscribers\Models;

use App\Events\Models\User\UserCreatedEvent;
use App\Listeners\Models\User\SendWelcomeEmailListener;
use Illuminate\Events\Dispatcher;

class UserSubscribe
{
    public function subscribe(Dispatcher $event)
    {
        $event->listen(UserCreatedEvent::class, SendWelcomeEmailListener::class);
    }
}
