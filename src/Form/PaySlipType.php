<?php

namespace App\Form;

use App\Entity\PaySlip;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class PaySlipType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('salary')
            ->add('startPeriod', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('endPeriod', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('payAt', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'transfer bank' => PaySlip::TYPE_BANK_TRANSFER,
                    'cheque' => PaySlip::TYPE_CHEQUE,
                    'cash' => PaySlip::TYPE_CASH
                ]
            ])
            ->add('paySlipLine', CollectionType::class, [
                'entry_type' => PaySlipLineType::class,
                'allow_add' => true,
                'allow_delete' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PaySlip::class,
        ]);
    }
}
