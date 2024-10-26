<?php

// src/Form/PaymentType.php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cardNumber', TextType::class, [
                'constraints' => [new Assert\NotBlank(), new Assert\Luhn(), new Assert\Regex('/^\d{12,19}$/')]
            ])
            ->add('expirationDate', TextType::class, [
                'constraints' => [new Assert\NotBlank(), new Assert\Regex('/^(0[1-9]|1[0-2])\/\d{2}$/')],
            ])
            ->add('cvv', TextType::class, [
                'constraints' => [new Assert\NotBlank(), new Assert\Regex('/^\d{3}$/')]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }
}
