<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
#[\Attribute]
class EmailExists extends Constraint
{
    public string $message = 'The email "{{ string }}" does not exist.';

    public function getTargets(): string|array
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
