<?php

namespace App\Form;

use App\Entity\Like;
use App\Entity\User;
use App\Entity\Post;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Unique;

class LikeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('idUser', NumberType::class, [
                'required' => true,
                'mapped' => false
            ])
            ->add('idPost', NumberType::class, [
                'required' => true,
                'mapped' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Like::class,
            'constraints' => [
                new UniqueEntity([
                    'fields' => ['idUser', 'idPost'],
                    'message' => 'Like déjà envoyé'
                ])
            ]
        ]);
    }
}
