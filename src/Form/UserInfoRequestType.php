<?php
// src/Form/UserInfoRequestType.php
namespace App\Form;

use App\Request\UserInfoRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserInfoRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [new Assert\NotBlank()],
            ])
            ->add('email', EmailType::class, [
                'constraints' => [new Assert\NotBlank(), new Assert\Email()],
            ])
            ->add('phone', TextType::class, [
                'constraints' => [new Assert\Regex('/^\d{10,15}$/')],
            ])
            ->add('subscriptionType', ChoiceType::class, [
                'choices' => ['Free' => 'free', 'Premium' => 'premium'],
                'constraints' => [new Assert\NotBlank()],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserInfoRequest::class,
        ]);
    }
}
