<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Flight;
use App\Enum\FlightTypeEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FlightType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number')
            ->add(
                'type',
                EnumType::class,
                [
                    'class' => FlightTypeEnum::class,
                ],
            )
            ->add(
                'tasks',
                CollectionType::class,
                [
                    'entry_type' => TaskType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                ],
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Flight::class,
            'block_name' => '',
        ]);
    }
}
