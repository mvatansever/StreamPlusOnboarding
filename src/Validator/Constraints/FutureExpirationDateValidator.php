<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class FutureExpirationDateValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        // Check if the expiration date is in the format MM/YY
        if (!preg_match('/^(0[1-9]|1[0-2])\/(\d{2})$/', $value)) {
            $this->context->buildViolation('Invalid expiration date format.')
                ->addViolation();

            return;
        }

        // Extract month and year from the value
        preg_match('/^(0[1-9]|1[0-2])\/(\d{2})$/', $value, $matches);
        $expirationMonth = (int) $matches[1];
        $expirationYear = (int) $matches[2];

        // Get the current date
        $currentDate = new \DateTime();
        $currentYear = (int) $currentDate->format('y');
        $currentMonth = (int) $currentDate->format('m');

        // Compare the expiration date with the current date
        if ($expirationYear < $currentYear
            || ($expirationYear === $currentYear && $expirationMonth <= $currentMonth)) {
            // If the date is not valid, add an error
            $this->context->buildViolation('The expiration date must be in the future.')
                ->addViolation();
        }
    }
}
