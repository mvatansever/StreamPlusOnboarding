<?php

namespace App\Form;

use App\Validator\Constraints\FutureExpirationDate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class PaymentRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cardNumber', TextType::class, [
                'constraints' => [new Assert\NotBlank(), new Assert\Luhn(), new Assert\Regex('/^\d{12,19}$/')],
            ])
            ->add('expirationDate', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Regex('/^(0[1-9]|1[0-2])\/\d{2}$/'),
                    new FutureExpirationDate(),
                ],
            ])
            ->add('cvv', TextType::class, [
                'constraints' => [new Assert\NotBlank(), new Assert\Regex('/^\d{3}$/')],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
