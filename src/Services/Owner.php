<?php

namespace Molitor\Shop\Services;

class Owner
{
    protected int|null $userId;

    protected string $sessionId;

    public function __construct()
    {
        $this->userId = auth()->check() ? (int)auth()->id() : null;
        $this->sessionId = session()->getId();
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function getSessionId(): string
    {
        return $this->sessionId;
    }
}
