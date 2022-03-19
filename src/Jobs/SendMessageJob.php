<?php

namespace Neiderruiz\SentryNotificationLaravel\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Neiderruiz\SentryNotificationLaravel\Controllers\SentryController;
use Neiderruiz\SentryNotificationLaravel\Controllers\SlackController;

class SendMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $event;


    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;

    public $timeout = 120;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $event)
    {
        $this->event = $event;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $resultMessage = SentryController::findErrorSentry($this->event);
        if (config('slack-config.slack.send_message')) {
            SlackController::sendMessage($resultMessage);
        }
    }
}
