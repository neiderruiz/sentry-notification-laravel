<?php

namespace Neiderruiz\SentryNotificationLaravel\Controllers;

use Illuminate\Support\Facades\Http;

class SentryController
{

    static function searchEventId(string $eventId)
    {
        $SENTRY_URL_API = config('slack-config.sentry.url_api');
        $SENTRY_ORGANIZATION = config('slack-config.sentry.organization');
        $SENTRY_PROJECT = config('slack-config.sentry.project');
        $url_sentry = "$SENTRY_URL_API/$SENTRY_ORGANIZATION/$SENTRY_PROJECT/events/$eventId/";

        $token_bearer = 'Bearer ' . config('slack-config.sentry.token');
        $response = Http::withHeaders(
            [
                'Authorization' => $token_bearer,
                'Content-Type' => 'application/json',
            ]

        )->get($url_sentry);


        return $response->json();
    }


    static function verifiedEnviroments()
    {
        $SENTRY_URL_API = config('slack-config.sentry.url_api');
        $SENTRY_ORGANIZATION = config('slack-config.sentry.organization');
        $SENTRY_PROJECT = config('slack-config.sentry.project');

        if (!$SENTRY_ORGANIZATION || !$SENTRY_PROJECT || !$SENTRY_URL_API) {
            $payload =  [
                'attachments' => [
                    [
                        'mrkdwn_in' => ['text'],
                        'color' => '#FF0000',
                        'pretext' => "Error: No se encontraron las variables de entorno para Slack",
                        'text' => "Verificar que se hayan configurado las variables de entorno en el archivo .env",
                    ],
                ],
            ];
            SlackController::sendMessage($payload);
            return false;
        }
        return true;
    }

    static function findErrorSentry(string $eventId)
    {
        if (self::verifiedEnviroments()) {

            for ($i = 0; $i <= 10; $i++) {
                sleep(2);
                $response = self::searchEventId($eventId);
                if (!isset($response['detail'])) {
                    $i = 10;
                }
            }

            return $response;
        }
    }
}
