<?php

namespace App\Validator\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
#[Attribute]
class EmailExists extends Constraint
{
    public string $message = 'The email "{{ string }}" is already in use.';

    public function getTargets()
    {
        return self::PROPERTY_CONSTRAINT; // Change this to PROPERTY_CONSTRAINT
    }
}
