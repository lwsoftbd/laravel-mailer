<?php

namespace Lwsoftbd\LaravelMailer\Listeners;

use Illuminate\Mail\Events\MessageSending;
use Lwsoftbd\LaravelMailer\Services\MailConfigManager;

class ResolveSmtpBeforeSend
{
    public function handle(MessageSending $event)
    {
        $notification = $event->data['notification'] ?? null;

        $queue = method_exists($notification,'smtpQueue')
            ? $notification->smtpQueue()
            : null;

        MailConfigManager::apply($queue);
    }
}
