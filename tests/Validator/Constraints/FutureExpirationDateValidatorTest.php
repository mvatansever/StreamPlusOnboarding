<?php

namespace App\Tests\Validator\Constraints;

use App\Validator\Constraints\FutureExpirationDate;
use App\Validator\Constraints\FutureExpirationDateValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class FutureExpirationDateValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator(): FutureExpirationDateValidator
    {
        return new FutureExpirationDateValidator();
    }

    public function expirationDateProvider()
    {
        return [
            // Valid expiration dates
            ['12/'.(new \DateTime())->add(new \DateInterval('P1Y'))->format('y'), 0],
            ['01/'.(new \DateTime())->add(new \DateInterval('P2Y'))->format('y'), 0],
            // Invalid expiration dates
            ['01/'.(new \DateTime())->sub(new \DateInterval('P1Y'))->format('y'), 1], // Past date
            ['not/a/date', 1], // Invalid format
            ['00/22', 1], // Invalid month
            ['13/22', 1], // Invalid month
        ];
    }

    /**
     * @dataProvider expirationDateProvider
     */
    public function testExpirationDate($expirationDate, $expectedViolationCount)
    {
        $this->validator->validate($expirationDate, new FutureExpirationDate());

        if ($expectedViolationCount > 0) {
            if ($expectedViolationCount === 1 && in_array($expirationDate, ['not/a/date', '00/22', '13/22'])) {
                $this->buildViolation('Invalid expiration date format.')
                    ->assertRaised();
            } else {
                $this->buildViolation('The expiration date must be in the future.')
                    ->assertRaised();
            }
        } else {
            $this->assertNoViolation();
        }
    }
}
