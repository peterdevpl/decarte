<?php

namespace OrderBundle\Form\Cart;

use OrderBundle\Entity\Order;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CartType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('items', CollectionType::class, [
                'label' => false,
                'entry_type' => CartItemType::class,
                'entry_options' => [
                    'label' => false,
                ],
            ])
            ->add('deliveryType', EntityType::class, [
                'choices' => $options['delivery_types'],
                'class' => 'OrderBundle:DeliveryType',
                'expanded' => true,
                'label' => 'Sposób dostawy',
                'multiple' => false,
            ])
            ->add('realizationType', EntityType::class, [
                'choices' => $options['realization_types'],
                'class' => 'OrderBundle:RealizationType',
                'expanded' => true,
                'label' => 'Tryb realizacji',
                'multiple' => false,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Przelicz i zapisz zmiany',
            ])
            ->add('save_and_order', SubmitType::class, [
                'label' => 'Złóż zamówienie',
                'attr' => ['class' => 'btn btn-primary'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(['data_class' => Order::class])
            ->setRequired(['realization_types', 'delivery_types']);
    }
}
