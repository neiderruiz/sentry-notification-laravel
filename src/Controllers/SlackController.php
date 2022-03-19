<?php

namespace Neiderruiz\SentryNotificationLaravel\Controllers;

use Illuminate\Support\Facades\Http;
class SlackController
{
    static function sendMessage($payload)
    {
        $message = self::createMessage($payload);
        if (!config('slack-config.slack.webhook_endpoint')) {
            return;
        }

        Http::withHeaders(
            [
                'Content-Type' => 'application/json',
            ]
        )->post(config('slack-config.slack.webhook_endpoint'), $message);
    }

    static function createMessage($response)
    {
        $SENTRY_ORGANIZATION = config('slack-config.sentry.organization');

        if (isset($response['detail'])) {
            return  [
                'attachments' => [
                    [
                        'mrkdwn_in' => ['text'],
                        'color' => '#FF0000',
                        'pretext' => 'Evento no encontrado',
                        'text' => 'Informacion del error',
                        // 'actions' => [
                        //     [
                        //         'type' => 'button',
                        //         'text' => 'Ver error ⇗',
                        //         'style' => 'danger',
                        //         'url' => $url_sentry,
                        //     ],
                        // ],
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
            $type =  $response['type'];
            $user_email = null;
            $user_id = null;
            $user_ip = null;
            if ($response['user']) {
                $user_email    = $response['user']['email'];
                $user_id = $response['user']['id'];
                $user_ip = $response['user']['ip_address'];
            }
            $error_url = $response['culprit'];

            return [
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
    }
}
