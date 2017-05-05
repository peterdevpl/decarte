<?php

namespace OrderBundle\Form;

use CustomerBundle\Form\CustomerType;
use OrderBundle\Entity\Order;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
                'attr' => [
                    'rows' => 3,
                ],
            ])
            ->add('deliveryType', EntityType::class, [
                'choices' => $options['delivery_types'],
                'class' => 'OrderBundle:DeliveryType',
                'expanded' => true,
                'label' => 'Sposób dostawy',
                'multiple' => false,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Dalej',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(['data_class' => Order::class])
            ->setRequired(['delivery_types']);
    }
}
