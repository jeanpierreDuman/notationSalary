<?php

namespace App\Form;

use App\Entity\Salary;
use App\Entity\TimeTable;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TimeTableType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('salary', EntityType::class, [
                'class' => Salary::class,
                'choice_label' => 'firstname',
            ])
            ->add('timeTableLine', CollectionType::class, [
                'entry_type' => TimeTableLineType::class,
                'allow_add' => true,
                'allow_delete' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TimeTable::class,
        ]);
    }
}
