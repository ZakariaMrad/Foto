<?php

namespace App\Form;

use App\Entity\Complaint;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


class ReportFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('idSubject', NumberType::class, [
                'required' => true,
                'mapped' => false,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez entrer l\'id du subject.',
                    ])
                ],
            ])
            ->add('subjectType', TextType::class, [
                'required' => true,
                'mapped' => false,
                'constraints' => [
                    new Assert\Choice([
                        'choices' => ['comment', 'post', 'album'],
                        'message' => 'Please select a valid subject type.',
                    ]),
                ]
            ])
            ->add('idSender', NumberType::class, [
                'required' => true,
                'mapped' => false,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez entrer l\'id du subject.',
                    ])
                ],
            ])
            ->add('idRecipient', NumberType::class, [
                'required' => true,
                'mapped' => false,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez entrer l\'id du subject.',
                    ])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Complaint::class,
        ]);
    }
}
