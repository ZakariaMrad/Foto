<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


class RegistrationFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Please enter your name',
                    ]),
                    new Assert\Length([
                        'min' => 2,
                        'minMessage' => 'Your name should be at least {{ limit }} characters',
                    ]),
                    new Assert\Length([
                        'max' => 50,
                        'maxMessage' => 'Your name should be no more than {{ limit }} characters',
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Please enter your email',
                    ]),
                    new Assert\Email([
                        'message' => 'The email "{{ value }}" is not a valid email.',
                    ])
                ],
            ])
            ->add('password', RepeatedType::class,[
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'required' => true,
                'first_options'  => [
                    'constraints' => [
                        new Assert\NotBlank([
                            'message' => 'Please enter your password',
                        ]),
                        new Assert\Length([
                            'min' => 6,
                            'minMessage' => 'Your password should be at least {{ limit }} characters',
                        ]),
                        new Assert\Length([
                            'max' => 50,
                            'maxMessage' => 'Your password should be no more than {{ limit }} characters',
                        ]),
                    ],
                ],
                'second_options' => [
                    'constraints' => [
                        new Assert\NotBlank([
                            'message' => 'Please repeat your password',
                        ]),
                        new Assert\Length([
                            'min' => 6,
                            'minMessage' => 'Your password should be at least {{ limit }} characters',
                        ]),
                        new Assert\Length([
                            'max' => 50,
                            'maxMessage' => 'Your password should be no more than {{ limit }} characters',
                        ]),
                    ],
                ],
            ])
            ->add('location', TextType::class, [
                'label' => 'Location',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Please enter your location',
                    ]),
                    new Assert\Length([
                        'min' => 2,
                        'minMessage' => 'Your location should be at least {{ limit }} characters',
                    ]),
                    new Assert\Length([
                        'max' => 255,
                        'maxMessage' => 'Your location should be no more than {{ limit }} characters',
                    ]),
                ],
            ])
            ->add('birthDate', DateType::class, [
                'required' => true,
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Please enter your birth date',
                    ])
            ]]);
            // $builder->get('birthDate')->addModelTransformer(new CallbackTransformer(
            //     function ($dateAsString) {
            //         // transform the string to a DateTime object
            //         return new \DateTime($dateAsString);
            //     },
            //     function ($dateAsDateTime) {
            //         // transform the DateTime object back to a string
            //         if ($dateAsDateTime instanceof \DateTimeInterface) {
            //             return $dateAsDateTime->format('Y-m-d');
            //         }
            //         return null; // Handle cases where $dateAsDateTime is not a DateTimeInterface
            //     }
            // ));
            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'constraints' => [
                new UniqueEntity([
                    'fields' => ['email'],
                    'message' => 'This email is already in use.',
                ]),
            ],
        ]);
    }
}
