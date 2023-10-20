<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


class CreatePostFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Titre : Veuillez entrer un titre.',
                    ]),
                    new Assert\Length([
                        'min' => 3,
                        'minMessage' => 'Titre: Le titre doit contenir au moins {{ limit }} caractères.',
                        'max' => 255,
                        'maxMessage' => 'Titre: Le titre ne peut pas contenir plus de {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('description', TextType::class, [
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Description: Veuillez entrer une description.',
                    ]),
                    new Assert\Length([
                        'min' => 3,
                        'minMessage' => 'Description: La description doit contenir au moins {{ limit }} caractères.',
                        'max' => 255,
                        'maxMessage' => 'Description: La description ne peut pas contenir plus de {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('isPublic', CheckboxType::class, [
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Publique: Veuillez entrer vrai ou faux.',
                    ]),
                    new Assert\Type([
                        'type' => 'bool',
                        'message' => 'Publique: Le champ doit être un booléen.',
                    ]),
                ],
            ])
            ->add('isFoto', CheckboxType::class, [
                'required' => true,
                'mapped' => false, 
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Album: Veuillez entrer vrai ou faux.',
                    ]),
                    new Assert\Type([
                        'type' => 'bool',
                        'message' => 'Album: Le champ doit être un booléen.',
                    ]),
                ]
            ])
            ->add('id', TextType::class, [
                'required' => true,
                'mapped' => false,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Id: Veuillez entrer un id.',
                    ]),
                    new Assert\Length([
                        'min' => 1,
                        'minMessage' => 'Id: L\'id doit contenir au moins {{ limit }} caractères.'
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
