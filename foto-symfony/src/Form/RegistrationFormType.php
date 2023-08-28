<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;




class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'required' => true,
                'label' => 'Email',
                'attr' => []
            ])
            ->add('lastName', TextType::class, [
                'required' => true,
                'label' => 'Last name',
                'attr' => []
            ])
            ->add('firstName', TextType::class, [
                'required' => true,
                'label' => 'First name',
                'attr' => []
            ])
            ->add('userName', TextType::class, [
                'required' => true,
                'label' => 'Username',
                'attr' => []
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => "Both passwords must be identical",
                'constraints' => [new Assert\Length(['min' => 6, 'minMessage' => "The password must contain at least {{ limit }} characters"])],
                'options' => ['attr' => ['class' => 'password_field']], // or 'class' => 'password-field'
                'required' => true,
                'first_options' => ['label' => "Password"],
                'second_options' => ['label' => "Password confirmation"]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
