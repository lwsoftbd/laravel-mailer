<?php

namespace Lwsoftbd\LaravelMailer\Support;

trait UsesSmtp
{
    public function smtpQueue(): ?string
    {
        return property_exists($this,'smtpQueue')
            ? $this->smtpQueue
            : null;
    }
}
