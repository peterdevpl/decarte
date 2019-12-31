<?php

declare(strict_types=1);

namespace Decarte\Shop\Form\Order;

use Decarte\Shop\Entity\Product\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class OrderSamplesItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('product', EntityType::class, [
                'class' => Product::class,
                'choices' => $options['products'],
                'label' => 'Zaproszenie nr __number__',
                'placeholder' => '--',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(['data_class' => Product::class])
            ->setRequired(['products']);
    }
}
