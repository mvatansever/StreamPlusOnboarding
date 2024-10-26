<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class FutureExpirationDateValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        // Check if the expiration date is in the format MM/YY
        if (preg_match('/^(0[1-9]|1[0-2])\/(\d{2})$/', $value, $matches)) {
            // Get the current date
            $currentDate = new \DateTime();
            // Get the current year and month
            $currentYear = (int)$currentDate->format('y');
            $currentMonth = (int)$currentDate->format('m');

            // Extract month and year from the value
            $expirationMonth = (int)$matches[1];
            $expirationYear = (int)$matches[2];

            // Compare the expiration date with the current date
            if ($expirationYear < $currentYear ||
                ($expirationYear === $currentYear && $expirationMonth <= $currentMonth)) {
                // If the date is not valid, add an error
                $this->context->buildViolation($constraint->message)
                    ->addViolation();
            }
        }
    }
}
