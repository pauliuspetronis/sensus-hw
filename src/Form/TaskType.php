<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Skill;
use App\Entity\Task;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add(
                'requiredSkills',
                EntityType::class,
                [
                    'class' => Skill::class,
                    'choice_label' => 'name',
                    'multiple' => true,
                    'expanded' => true,
                ],
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
            'block_name' => '',
        ]);
    }
}
