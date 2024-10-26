<?php

namespace App\Tests\Validator\Constraints;

use App\Entity\User;
use App\Validator\Constraints\EmailExists;
use App\Validator\Constraints\EmailExistsValidator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class EmailExistsValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator(): EmailExistsValidator
    {
        return new EmailExistsValidator($this->createMock(EntityManagerInterface::class));
    }

    public function testEmailExists()
    {
        $mockRepository = $this->createMock(EntityRepository::class);
        $mockEntityManager = $this->createMock(EntityManagerInterface::class);

        $mockEntityManager->method('getRepository')->willReturn($mockRepository);
        $mockRepository->method('findOneBy')->willReturn(new User());

        $this->validator = new EmailExistsValidator($mockEntityManager);
        $this->validator->initialize($this->context);

        $this->validator->validate('existing@example.com', new EmailExists());

        $this->buildViolation('The email "{{ string }}" is already in use.')
            ->setParameter('{{ string }}', 'existing@example.com')
            ->assertRaised();
    }

    public function testEmailDoesNotExist()
    {
        $mockRepository = $this->createMock(EntityRepository::class);
        $mockEntityManager = $this->createMock(EntityManagerInterface::class);

        $mockEntityManager->method('getRepository')->willReturn($mockRepository);
        $mockRepository->method('findOneBy')->willReturn(null);

        $this->validator = new EmailExistsValidator($mockEntityManager);
        $this->validator->initialize($this->context);

        $this->validator->validate('new@example.com', new EmailExists());

        $this->assertNoViolation();
    }

    public function testNullEmail()
    {
        $this->validator->validate(null, new EmailExists());

        $this->assertNoViolation();
    }

    public function testEmptyEmail()
    {
        $this->validator->validate('', new EmailExists());

        $this->assertNoViolation();
    }
}
