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
                'label' => 'Nom',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Nom: Veuillez entrer votre nom.',
                    ]),
                    new Assert\Length([
                        'min' => 2,
                        'minMessage' => 'Nom: Votre nom doit contenir au moins {{ limit }} caractères.',
                    ]),
                    new Assert\Length([
                        'max' => 50,
                        'maxMessage' => 'Nom: Votre nom doit contenir au maximum {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Courriel',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Courriel: Veuillez entrer votre courriel.',
                    ]),
                    new Assert\Email([
                        'message' => 'Courriel: Le courriel {{ value }} n\'est pas valide.',
                    ])
                ],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'label' => 'Mot de passe',
                'invalid_message' => 'Mot de passe: Les mots de passe doivent correspondre.',
                'required' => true,
                'first_options'  => [
                    'constraints' => [
                        new Assert\NotBlank([
                            'message' => 'Mot de passe: Veuillez entrer votre mot de passe.',
                        ]),
                        new Assert\Length([
                            'min' => 6,
                            'minMessage' => 'Mot de passe: Votre mot de passe doit contenir au moins {{ limit }} caractères.',
                        ]),
                        new Assert\Length([
                            'max' => 50,
                            'maxMessage' => 'Mot de passe: Votre mot de passe doit contenir au maximum {{ limit }} caractères.',
                        ]),
                    ],
                ],
                'second_options' => [
                    'constraints' => [
                        new Assert\NotBlank([
                            'message' => 'Confirmation mot de passe: Veuillez reentrer votre mot de passe.',
                        ]),
                        new Assert\Length([
                            'min' => 6,
                            'minMessage' => 'Confirmation mot de passe: Votre mot de passe doit contenir au moins {{ limit }} caractères.',
                        ]),
                        new Assert\Length([
                            'max' => 50,
                            'maxMessage' => 'Confirmation mot de passe: Votre mot de passe doit contenir au maximum {{ limit }} caractères.',
                        ]),
                    ],
                ],
            ])
            ->add('location', TextType::class, [
                'label' => 'Adresse',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Adresse: Veuillez entrer votre adresse.',
                    ]),
                    new Assert\Length([
                        'min' => 2,
                        'minMessage' => 'Adresse: Votre adresse doit contenir au moins {{ limit }} caractères',
                    ]),
                    new Assert\Length([
                        'max' => 255,
                        'maxMessage' => 'Adresse: Votre adresse doit contenir au maximum {{ limit }} caractères',
                    ]),
                ],
            ])
            ->add('birthDate', DateType::class, [
                'required' => true,
                'label' => 'Date de naissance',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Date de naissance: Veuillez entrer votre date de naissance.',
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'constraints' => [
                new UniqueEntity([
                    'fields' => ['email'],
                    'message' => 'Cette adresse courriel est déjà utilisée.',
                ]),
            ],
        ]);
    }
}
