<?php
// src/Validator/Constraints/FutureExpirationDate.php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class FutureExpirationDate extends Constraint
{
    public $message = 'The expiration date must be in the future.';
}
