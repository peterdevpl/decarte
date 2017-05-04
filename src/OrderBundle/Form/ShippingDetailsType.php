<?php

namespace OrderBundle\Order\Form;

use AppBundle\Customer\Form\CustomerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShippingDetailsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('customer', CustomerType::class)
            ->add('notes', TextareaType::class, [
                'label' => 'Uwagi lub pytania',
                'attributes' => [
                    'rows' => 3,
                ],
            ])
            ->add('delivery', ChoiceType::class, [
                'choices' => $options['delivery_types'],
                'expanded' => true,
                'label' => 'Sposób dostawy',
                'multiple' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['delivery_types']);
    }
}
