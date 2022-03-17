<?php

namespace Neiderruiz\SentryNotificationLaravel\Controllers;

use Illuminate\Support\Facades\Http;

class SlackController
{
    static function sendMessage(array $payload)
    {
        if (!config('slack-config.slack.webhook_endpoint')) {
            return;
        }

        $response = Http::withHeaders(
            [
                'Content-Type' => 'application/json',
            ]
        )->post(config('slack-config.slack.webhook_endpoint'), $payload);
        dd($response, config('slack-config.slack.webhook_endpoint'), $payload);
        dd(config('slack-config.slack.webhook_endpoint'));
    }


    static function sendMessageEventId(string $eventId)
    {

        if (!SentryController::verifiedEnviroments()) {
            return;
        }


        $paylaod = SentryController::createMessage($eventId);

        SlackController::sendMessage($paylaod);
        dd($paylaod, 'neider');
    }
}
