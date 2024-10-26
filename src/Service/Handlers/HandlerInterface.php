<?php

namespace App\Service\Handlers;

use App\Request\OnboardProcessStepRequest;

interface HandlerInterface
{
    public function handle(OnboardProcessStepRequest $stepRequest): HandlerResponse;
}
