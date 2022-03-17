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

        $token_bearer = 'Bearer ' . env('SENTRY_TOKEN');
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

    static function createMessage(string $eventId)
    {
        // variables
        $SENTRY_URL_API = config('slack-config.sentry.url_api');
        $SENTRY_ORGANIZATION = config('slack-config.sentry.organization');
        $SENTRY_PROJECT = config('slack-config.sentry.project');
        $url_sentry = "$SENTRY_URL_API/$SENTRY_ORGANIZATION/$SENTRY_PROJECT/events/$eventId/";

        for ($i = 0; $i <= 10; $i++) {
            sleep(2);
            $response = self::searchEventId($eventId);
            if (!isset($response['detail'])) {
                $i = 10;
            }
        }

        if (isset($response['detail'])) {
            $payload = [
                'attachments' => [
                    [
                        'mrkdwn_in' => ['text'],
                        'color' => '#FF0000',
                        'pretext' => 'Evento no encontrado',
                        'text' => 'Informacion del error',
                        'actions' => [
                            [
                                'type' => 'button',
                                'text' => 'Ver error ⇗',
                                'style' => 'danger',
                                'url' => $url_sentry,
                            ],
                        ],
                    ]
                ],
            ];
        } else {

            $issue = $response['groupID'];
            $url_error = "https://sentry.io/organizations/$SENTRY_ORGANIZATION/issues/$issue/";
            $messare_error = $response['metadata']['type'];
            $subtitle_error = $response['metadata']['value'];
            $title = "*<$url_error|$messare_error>*";
            $time = $response['dateReceived'];
            $user_email    = $response['user']['email'];
            $user_id = $response['user']['id'];
            $user_ip = $response['user']['ip_address'];
            $error_url = $response['culprit'];
            $type = $response['type'];

            $payload =  [
                'attachments' => [
                    [
                        'mrkdwn_in' => ['text'],
                        'color' => '#FF0000',
                        'pretext' => $title,
                        'text' => $subtitle_error,
                        'fields' => [
                            [
                                'title' => "Tipo: $type",
                                'value' => "Hora: $time",
                                'short' => false,
                            ],
                            [
                                'title' => "Usuario:",
                                'value' => $user_email ? $user_email : 'null',
                                'short' => true,
                            ],
                            [
                                'title' => "id:",
                                'value' => $user_id ? $user_id : 'null',
                                'short' => true,
                            ],
                            [
                                'title' => "Ip_user:",
                                'value' => $user_ip ? $user_ip : 'null',
                                'short' => true,
                            ],
                            [
                                'title' => "*ENPOINT ERROR*",
                                'value' => $error_url,
                                'short' => false,
                            ],
                        ],

                        'actions' => [
                            [
                                'type' => 'button',
                                'text' => 'Ver error ⇗',
                                'style' => 'danger',
                                'url' => $url_error,
                            ],
                        ],
                        'footer' => 'Sentry',
                        'footer_icon' => 'https://avatars.slack-edge.com/2020-04-24/1109268338864_716700387c3d322eab67_512.png',
                        'ts' => 'ts',

                    ],
                ],
            ];
        }

        return $payload;
    }
}
