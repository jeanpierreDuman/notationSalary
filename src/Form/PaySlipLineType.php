<?php

namespace App\Form;

use App\Entity\PaySlipLine;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaySlipLineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', null, ['attr' => ['class' => 'form-control']])
            ->add('description', null, ['attr' => ['class' => 'form-control']])
            ->add('base', null, ['attr' => ['class' => 'form-control']])
            ->add('rateSalary',null, ['attr' => ['class' => 'form-control'], 'required' => false])
            ->add('amountSalary', null, ['required' => false, 'attr' => ['class' => 'form-control', 'readonly' => true]])
            ->add('rateEmploye',null, ['attr' => ['class' => 'form-control'], 'required' => false])
            ->add('amountEmploye', null, ['required' => false, 'attr' => ['class' => 'form-control', 'readonly' => true]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PaySlipLine::class,
        ]);
    }
}
