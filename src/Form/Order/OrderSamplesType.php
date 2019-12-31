<?php

declare(strict_types=1);

namespace Decarte\Shop\Form\Order;

use Decarte\Shop\Entity\Order\DeliveryType;
use Decarte\Shop\Entity\Order\Order;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class OrderSamplesType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(['data_class' => Order::class])
            ->setRequired(['products', 'delivery_types']);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', HiddenType::class)
            ->add('items', CollectionType::class, [
                'label' => false,
                'entry_type' => OrderSamplesItemType::class,
                'entry_options' => ['products' => $options['products'], 'label' => false],
                'allow_add' => true,
            ])
            ->add('notes', TextType::class, ['label' => 'form.notes', 'required' => false])
            ->add('email', EmailType::class, ['label' => 'form.email'])
            ->add('phone', TextType::class, ['label' => 'form.phone'])
            ->add('name', TextType::class, ['label' => 'form.name'])
            ->add('street', TextType::class, ['label' => 'form.address'])
            ->add('postal_code', TextType::class, ['label' => 'form.zipcode'])
            ->add('city', TextType::class, ['label' => 'form.city'])
            ->add('country', CountryType::class, [
                'label' => 'form.country',
                'preferred_choices' => [
                    'PL', 'BE', 'HR', 'CZ', 'DK', 'FI', 'FR', 'GR', 'DE', 'IE', 'LT', 'LU', 'NL', 'NO', 'RO', 'SK',
                    'GB', 'IT',
                ],
            ])
            ->add('delivery_type', EntityType::class, [
                'choices' => $options['delivery_types'],
                'class' => DeliveryType::class,
                'expanded' => true,
                'label' => 'form.deliveryType',
                'multiple' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'order.samples.submit',
                'attr' => ['class' => 'btn btn-primary'],
            ]);
    }
}
