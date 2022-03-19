<?php

namespace Neiderruiz\SentryNotificationLaravel\Controllers;

use Neiderruiz\SentryNotificationLaravel\Jobs\SendMessageJob;

class SentryCaptureController
{
    static function capture(string $event)
    {
        SendMessageJob::dispatch($event)->delay(now()->addSeconds(10)->timezone('America/Bogota'));
    }
}
