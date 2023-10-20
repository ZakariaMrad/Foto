<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


class LoginFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',EmailType::class,[
                'required' => true,
                'property_path' => 'email',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Courriel: Veuillez entrer votre courriel.',
                    ]),
                    new Assert\Email([
                        'message' => 'Courriel: Le courriel {{ value }} n\'est pas valide.',
                    ]),
                ],
            ])
            ->add('password',PasswordType::class,[
                'required' => true,
                'label' => 'Mot de passe',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Mot de passe:Veuillez entrer votre mot de passe.',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
