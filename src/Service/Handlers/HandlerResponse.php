<?php

namespace App\Service\Handlers;

class HandlerResponse implements \JsonSerializable
{
    public function __construct(private bool $success, private string $redirectUrl)
    {
    }

    /**
     * @return array{
     *     success: bool,
     *     redirectUrl: string
     * }
     */
    public function jsonSerialize(): array
    {
        return [
            'success' => $this->success,
            'redirectUrl' => $this->redirectUrl,
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
