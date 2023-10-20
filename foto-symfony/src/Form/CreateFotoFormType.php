<?php

namespace App\Form;

use App\Entity\Foto;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class CreateFotoFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,[
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Nom: Veuillez entrer le nom de la photo',
                    ]),
                ],
            ])
            ->add('description',TextType::class,[
                'required' => false,
            ])
            ->add('base64image',TextType::class,[
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Source image: Veuillez fournir les sources de l\'image',
                    ]),
                ],
                'mapped' => false
            ])
            ->add('isNSFW', CheckboxType::class,[
                'required' => true,
                'constraints' => [
                    //TODO: Fix le blank
                    new Assert\Type([
                        'type' => 'bool',
                        'message' => 'Le champ doit être un booléen.',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Foto::class,
        ]);
    }
}
