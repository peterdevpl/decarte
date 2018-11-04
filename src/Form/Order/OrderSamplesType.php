<?php

declare(strict_types=1);

namespace Decarte\Shop\Form\Order;

use Decarte\Shop\Entity\Order\Samples\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
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
            ->setRequired(['products']);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('items', CollectionType::class, [
                'label' => false,
                'entry_type' => SampleType::class,
                'entry_options' => [
                    'label' => false,
                    'products' => $options['products'],
                ],
            ])
            ->add('notes', TextType::class, ['label' => 'form.notes', 'required' => false])
            ->add('email', EmailType::class, ['label' => 'form.email'])
            ->add('phone', TextType::class, ['label' => 'form.phone'])
            ->add('name', TextType::class, ['label' => 'form.name'])
            ->add('address', TextType::class, ['label' => 'form.address'])
            ->add('postal_code', TextType::class, ['label' => 'form.zipcode'])
            ->add('city', TextType::class, ['label' => 'form.city'])
            ->add('submit', SubmitType::class, [
                'label' => 'order.samples',
                'attr' => ['class' => 'btn btn-primary'],
            ]);
    }
}
