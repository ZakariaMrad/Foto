<?php

namespace App\Form;

use App\Entity\Album;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


class CreateAlbumFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Titre: Veuillez entrer un titre',
                    ]),
                ],
            ])
            ->add('notes', TextType::class, [
                'required' => false,
            ])
            ->add('description', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Description: Veuillez entrer une description',
                    ]),
                ],
            ])
            // ->add('isPublic', CheckboxType::class, [
            //     'required' => true,
            //     'false_values' => ['false'],
            //     'constraints' => [
            //         new Assert\Type([
            //             'type' => 'bool',
            //             'message' => 'Le champ doit être un booléen.',
            //         ]),
            //     ],
            // ])
            ->add('type', TextType::class, [
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Type: Veuillez entrer un type',
                    ]),
                    new Assert\Choice([
                        'choices' => ['grid', 'carousel'],
                        'message' => 'Type: Veuillez entrer un type valide (grid ou carousel)',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Album::class,
        ]);
    }
}
