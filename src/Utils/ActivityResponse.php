<?php

namespace Src\Utils;

use App\Document\Activite;

class ActivityResponse
{
    private ?Activite $activity;
    private ?string $message;

    public function __construct(?Activite $activity = null, ?string $message = null)
    {
        $this->activity = $activity;
        $this->message = $message;
    }

    public function getActivity(): ?Activite
    {
        return $this->activity;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function hasActivity(): bool
    {
        return $this->activity !== null;
    }
}