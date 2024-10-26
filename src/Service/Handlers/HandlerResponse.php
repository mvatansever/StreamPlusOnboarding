<?php

namespace App\Service\Handlers;

class HandlerResponse implements \JsonSerializable
{
    public function __construct(private bool $success, private string $redirectUrl, private array $errors = []){}

    public function jsonSerialize(): array
    {
        return [
            'success' => $this->success,
            'redirectUrl' => $this->redirectUrl,
            'errors' => $this->errors,
        ];
    }

    public function getRedirectUrl(): string
    {
        return $this->redirectUrl;
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }
}