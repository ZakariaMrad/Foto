<?php

namespace App\Form;

use App\Entity\UserBlock;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints as Assert;


class UserBlockFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('reason', TextType::class, [
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Motif: Veuillez entrer un motif.',
                    ]),
                    new Assert\Length(
                        [
                            'min' => 1,
                            'minMessage' => 'Motif: Il doit y avoir au moins {{limit}} caractère.',
                            'max' => 255,
                            'maxMessage' => 'Motif: Il ne peut pas y avoir plus de {{limit}} caractères.'
                        ]
                    ),
                ],
            ])
            ->add('idUser', NumberType::class, [
                'required' => true,
                'mapped' => false,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'IdUser: Veuillez entrer un idUser.',
                    ])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserBlock::class,
        ]);
    }
}
