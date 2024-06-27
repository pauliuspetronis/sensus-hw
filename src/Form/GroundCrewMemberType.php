<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\GroundCrewMember;
use App\Entity\Skill;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroundCrewMemberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName')
            ->add(
                'skills',
                EntityType::class,
                [
                    'class' => Skill::class,
                    'choice_label' => 'name',
                    'expanded' => true,
                    'multiple' => true,
                ],
            )
            ->add(
                'groundCrewMemberCertifications',
                CollectionType::class,
                [
                    'entry_type' => GroundCrewMemberCertificationType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                ],
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GroundCrewMember::class,
            'block_name' => '',
        ]);
    }
}
