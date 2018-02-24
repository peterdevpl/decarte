<?php

namespace Decarte\Shop\Form\Order;

use Decarte\Shop\Entity\Order\Samples\OrderItem;
use Decarte\Shop\Entity\Product\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SampleType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(['compound' => true, 'data_class' => OrderItem::class])
            ->setRequired(['products']);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('product', EntityType::class, [
                'class' => Product::class,
                'choices' => $options['products'],
                'label' => 'model',
                'placeholder' => '--',
                'required' => false,
            ]);
    }
}
