<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('addressLine1', TextType::class, ['constraints' => [new Assert\NotBlank(), new Assert\Length(null, 10)]])
            ->add('addressLine2', TextType::class, ['required' => false])
            ->add('city', TextType::class, ['constraints' => [new Assert\NotBlank()]])
            ->add('postalCode', TextType::class, ['constraints' => [new Assert\NotBlank()]])
            ->add('stateProvince', TextType::class, ['constraints' => [new Assert\NotBlank()]])
            ->add('country', CountryType::class, ['constraints' => [new Assert\NotBlank()]]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }
}