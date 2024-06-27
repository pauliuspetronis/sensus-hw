<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Certification;
use App\Entity\GroundCrewMemberCertification;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroundCrewMemberCertificationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('expirationDate')
            ->add(
                'certification',
                EntityType::class,
                [
                    'class' => Certification::class,
                ],
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GroundCrewMemberCertification::class,
            'block_name' => '',
        ]);
    }
}
