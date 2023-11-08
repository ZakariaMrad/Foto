<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ModifyAccountFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('picturePath', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Photo de profil : Veuillez entrer une photo de profil.',
                    ])
                ],
            ])
            ->add('bio', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Bio : Veuillez entrer une bio.',
                    ]),
                    new Assert\Length([
                        'max' => 1024,
                        'maxMessage' => 'Bio: La bio ne peut pas contenir plus de {{ limit }} caractères.',
                    ])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
